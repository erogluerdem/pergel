<?php
/**
 * PERGEL - cPanel Veritabanı Migration & Kurulum Aracı
 *
 * Kullanım:
 * 1. Tarayıcıdan https://alanadiniz.com/cpanel-migrate.php adresine gidin.
 * 2. Güvenlik şifresini girin (.env içinde CPANEL_DEPLOY_KEY tanımlıysa o değer,
 *    tanımlı değilse aşağıdaki $defaultAccessKey değeri kullanılır).
 * 3. İşlemleriniz bittikten sonra ekranın altındaki "Bu Dosyayı Sunucudan Sil"
 *    butonuna basarak dosyayı güvenliğiniz için kaldırın.
 */

@ini_set('display_errors', '1');
@ini_set('memory_limit', '512M');
@set_time_limit(0);

session_start();

// -----------------------------------------------------------------------------
// 1. GÜVENLİK ANAHTARI AYARI
// .env dosyasında CPANEL_DEPLOY_KEY=... varsa otomatik onu okur.
// Yoksa buradaki varsayılan şifreyi kullanır:
// -----------------------------------------------------------------------------
$defaultAccessKey = 'pergel2026!';

// -----------------------------------------------------------------------------
// 2. LARAVEL KÖK DİZİNİNİ OTOMATİK BUL (Standart veya cPanel public_html yapısı)
// -----------------------------------------------------------------------------
$possibleBasePaths = [
    realpath(__DIR__ . '/..'),              // Standart: public/cpanel-migrate.php
    realpath(__DIR__),                      // Ana dizine atıldıysa
    realpath(__DIR__ . '/../pergel'),       // public_html + üst dizinde pergel klasörü
    realpath(__DIR__ . '/../laravel'),      // public_html + üst dizinde laravel klasörü
];

$basePath = null;
foreach ($possibleBasePaths as $candidate) {
    if ($candidate && file_exists($candidate . '/vendor/autoload.php') && file_exists($candidate . '/bootstrap/app.php')) {
        $basePath = $candidate;
        break;
    }
}

if (!$basePath) {
    http_response_code(500);
    die('<h3>Hata: Laravel ana dizini (vendor/autoload.php) bulunamadı.</h3><p>Lütfen bu dosyayı projenin <code>public/</code> (veya <code>public_html/</code>) klasörüne yüklediğinizden ve <code>vendor</code> klasörünün mevcut olduğundan emin olun.</p>');
}

// .env içerisinden CPANEL_DEPLOY_KEY varsa oku
$envAccessKey = null;
$envFile = $basePath . '/.env';
if (file_exists($envFile) && is_readable($envFile)) {
    $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_starts_with($line, 'CPANEL_DEPLOY_KEY=')) {
            $val = trim(substr($line, strlen('CPANEL_DEPLOY_KEY=')));
            $val = trim($val, "\"'");
            if ($val !== '') {
                $envAccessKey = $val;
            }
            break;
        }
    }
}

$activeKey = $envAccessKey ?: $defaultAccessKey;

// -----------------------------------------------------------------------------
// 3. OTURUM & ŞİFRE DOĞRULAMA
// -----------------------------------------------------------------------------
if (empty($_SESSION['cpanel_csrf_token'])) {
    $_SESSION['cpanel_csrf_token'] = bin2hex(random_bytes(24));
}

$authError = null;
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $inputKey = (string) ($_POST['access_key'] ?? '');
    if (hash_equals($activeKey, $inputKey)) {
        $_SESSION['cpanel_migrate_authenticated'] = true;
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    } else {
        usleep(500000); // Brute-force gecikmesi
        $authError = 'Geçersiz güvenlik anahtarı!';
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    unset($_SESSION['cpanel_migrate_authenticated']);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$isAuthenticated = !empty($_SESSION['cpanel_migrate_authenticated']);

// -----------------------------------------------------------------------------
// 4. LARAVEL CONSOLE KERNEL BAŞLATMA VE KOMUT ÇALIŞTIRMA
// -----------------------------------------------------------------------------
$commandOutput = '';
$commandTitle = '';
$commandSuccess = null;
$dbStatus = ['connected' => false, 'driver' => '-', 'database' => '-', 'error' => null];

if ($isAuthenticated) {
    require_once $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';

    /** @var \Illuminate\Contracts\Console\Kernel $kernel */
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Henüz cache veya sessions tablosu oluşturulmamışsa hata vermemesi için belleğe al
    config([
        'cache.default' => 'array',
        'session.driver' => 'array',
    ]);

    // Veritabanı bağlantı kontrolü
    try {
        $conn = \Illuminate\Support\Facades\DB::connection();
        $conn->getPdo();
        $dbStatus['connected'] = true;
        $dbStatus['driver'] = $conn->getDriverName();
        $dbStatus['database'] = $conn->getDatabaseName();
    } catch (\Throwable $e) {
        $dbStatus['error'] = $e->getMessage();
    }

    // POST İşlemleri
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_command'])) {
        $csrf = (string) ($_POST['csrf_token'] ?? '');
        if (!hash_equals($_SESSION['cpanel_csrf_token'], $csrf)) {
            $commandSuccess = false;
            $commandTitle = 'Güvenlik Hatası';
            $commandOutput = 'CSRF doğrulaması başarısız oldu. Sayfayı yenileyip tekrar deneyin.';
        } else {
            $cmd = (string) $_POST['run_command'];

            try {
                switch ($cmd) {
                    case 'status':
                        $commandTitle = 'Migration Durumu (migrate:status)';
                        \Illuminate\Support\Facades\Artisan::call('migrate:status');
                        $commandOutput = \Illuminate\Support\Facades\Artisan::output();
                        $commandSuccess = true;
                        break;

                    case 'migrate':
                        $commandTitle = 'Veritabanı Migration (migrate --force)';
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        $commandOutput = \Illuminate\Support\Facades\Artisan::output();
                        $commandSuccess = true;
                        break;

                    case 'seed_core':
                        $commandTitle = 'Temel Roller & Yönetici Kullanıcısı (RolesAndPermissionsSeeder + UserSeeder)';
                        $out = [];
                        \Illuminate\Support\Facades\Artisan::call('db:seed', [
                            '--class' => 'Database\\Seeders\\RolesAndPermissionsSeeder',
                            '--force' => true,
                        ]);
                        $out[] = "[RolesAndPermissionsSeeder]\n" . trim(\Illuminate\Support\Facades\Artisan::output());

                        \Illuminate\Support\Facades\Artisan::call('db:seed', [
                            '--class' => 'Database\\Seeders\\UserSeeder',
                            '--force' => true,
                        ]);
                        $out[] = "[UserSeeder]\n" . trim(\Illuminate\Support\Facades\Artisan::output());

                        $commandOutput = implode("\n\n", $out);
                        $commandSuccess = true;
                        break;

                    case 'seed_all':
                        $commandTitle = 'Tüm Seeder Verilerini Yükle (db:seed --force)';
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                        $commandOutput = \Illuminate\Support\Facades\Artisan::output();
                        $commandSuccess = true;
                        break;

                    case 'clear_cache':
                        $commandTitle = 'Önbellek Temizleme (config / route / view / cache)';
                        $out = [];
                        foreach (['config:clear', 'route:clear', 'view:clear', 'cache:clear'] as $artCmd) {
                            \Illuminate\Support\Facades\Artisan::call($artCmd);
                            $out[] = ">>> php artisan {$artCmd}\n" . trim(\Illuminate\Support\Facades\Artisan::output());
                        }
                        $commandOutput = implode("\n\n", $out);
                        $commandSuccess = true;
                        break;

                    case 'storage_link':
                        $commandTitle = 'Storage Sembolik Bağlantısı (storage:link)';
                        \Illuminate\Support\Facades\Artisan::call('storage:link');
                        $commandOutput = \Illuminate\Support\Facades\Artisan::output();
                        $commandSuccess = true;
                        break;

                    case 'self_delete':
                        $deleted = @unlink(__FILE__);
                        if ($deleted) {
                            unset($_SESSION['cpanel_migrate_authenticated']);
                            die('<div style="font-family:sans-serif;max-width:600px;margin:60px auto;padding:24px;border:1px solid #16a34a;background:#f0fdf4;border-radius:8px;color:#166534;"><h2>✅ cpanel-migrate.php Başarıyla Silindi</h2><p>Dosya sunucudan kalıcı olarak kaldırıldı. Ana sayfaya dönebilirsiniz: <a href="/">Ana Sayfa</a></p></div>');
                        } else {
                            $commandSuccess = false;
                            $commandTitle = 'Dosya Silinemedi';
                            $commandOutput = 'Dosya izinleri nedeniyle otomatik silinemedi. Lütfen cPanel Dosya Yöneticisi üzerinden public/cpanel-migrate.php dosyasını manuel olarak silin.';
                        }
                        break;

                    default:
                        $commandSuccess = false;
                        $commandTitle = 'Bilinmeyen Komut';
                        $commandOutput = 'Geçersiz işlem seçildi.';
                }
            } catch (\Throwable $e) {
                $commandSuccess = false;
                $commandOutput = "HATA: " . $e->getMessage() . "\n\nDosya: " . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>PERGEL | cPanel Migration & Kurulum Aracı</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 24px;
            line-height: 1.5;
        }
        .container {
            max-width: 920px;
            margin: 0 auto;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        h1 { margin-top: 0; font-size: 22px; color: #f8fafc; display: flex; align-items: center; justify-content: space-between; }
        .badge {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
        }
        .badge-ok { background: #065f46; color: #6ee7b7; }
        .badge-err { background: #7f1d1d; color: #fca5a5; }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            background: #0f172a;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #334155;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .info-item strong { display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }
        .action-card {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .action-card h3 { margin: 0 0 6px 0; font-size: 15px; color: #f1f5f9; }
        .action-card p { margin: 0 0 14px 0; font-size: 12.5px; color: #94a3b8; }
        button {
            cursor: pointer;
            border: none;
            border-radius: 6px;
            padding: 10px 14px;
            font-weight: 600;
            font-size: 13px;
            transition: opacity 0.15s;
            width: 100%;
        }
        button:hover { opacity: 0.92; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #059669; color: #fff; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-secondary { background: #475569; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .terminal {
            background: #020617;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 16px;
            font-family: Consolas, Monaco, monospace;
            font-size: 13px;
            white-space: pre-wrap;
            word-break: break-word;
            max-height: 440px;
            overflow-y: auto;
            color: #cbd5e1;
        }
        .input-text {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #475569;
            background: #0f172a;
            color: #f8fafc;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13.5px;
        }
        .alert-danger { background: #450a0a; border: 1px solid #dc2626; color: #fecaca; }
        .alert-success { background: #064e3b; border: 1px solid #10b981; color: #a7f3d0; }
        .alert-warn { background: #451a03; border: 1px solid #f59e0b; color: #fde68a; }
    </style>
</head>
<body>
<div class="container">
    <h1>
        <span>🛠️ PERGEL cPanel Migration & Bakım Aracı</span>
        <?php if ($isAuthenticated): ?>
            <form method="post" style="margin:0;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btn-secondary" style="width:auto;padding:6px 12px;font-size:12px;">Çıkış Yap</button>
            </form>
        <?php endif; ?>
    </h1>

    <?php if (!$isAuthenticated): ?>
        <p style="color:#94a3b8;font-size:14px;">
            cPanel üzerinde SSH/Terminal erişimi olmadan migration, seeder ve önbellek işlemlerini çalıştırmak için güvenlik anahtarınızı girin.
        </p>
        <?php if ($authError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($authError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" style="max-width:400px;">
            <input type="hidden" name="action" value="login">
            <label style="display:block;margin-bottom:6px;font-size:13px;color:#cbd5e1;">Güvenlik Anahtarı</label>
            <input type="password" name="access_key" class="input-text" placeholder="Şifreyi girin..." required autofocus>
            <button type="submit" class="btn-primary">Giriş Yap</button>
        </form>
    <?php else: ?>

        <div class="info-grid">
            <div class="info-item">
                <strong>PHP Sürümü</strong>
                <span><?= htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <strong>Proje Dizini</strong>
                <span><?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <strong>Veritabanı Durumu</strong>
                <?php if ($dbStatus['connected']): ?>
                    <span class="badge badge-ok">Bağlı (<?= htmlspecialchars($dbStatus['driver'] . ' / ' . $dbStatus['database'], ENT_QUOTES, 'UTF-8') ?>)</span>
                <?php else: ?>
                    <span class="badge badge-err">Bağlantı Hatası</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$dbStatus['connected'] && $dbStatus['error']): ?>
            <div class="alert alert-danger">
                <strong>Veritabanı Bağlantı Hatası:</strong> <?= htmlspecialchars($dbStatus['error'], ENT_QUOTES, 'UTF-8') ?><br>
                <small>Lütfen <code>.env</code> dosyasındaki <code>DB_DATABASE</code>, <code>DB_USERNAME</code> ve <code>DB_PASSWORD</code> bilgilerini cPanel MySQL Veritabanları bölümündeki bilgilerle kontrol edin.</small>
            </div>
        <?php endif; ?>

        <div class="alert alert-warn">
            ⚠️ <strong>Güvenlik Uyarısı:</strong> Kurulum ve migration işlemleriniz tamamlandığında en alttaki <strong>"Bu Dosyayı Sunucudan Sil"</strong> butonuna basarak bu dosyayı mutlaka kaldırın.
        </div>

        <div class="actions-grid">
            <div class="action-card">
                <div>
                    <h3>1. Migration Durumu</h3>
                    <p>Hangi veritabanı tablolarının taşındığını ve bekleyen migration dosyalarını listeler (<code>migrate:status</code>).</p>
                </div>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="run_command" value="status">
                    <button type="submit" class="btn-secondary">Durumu Kontrol Et</button>
                </form>
            </div>

            <div class="action-card">
                <div>
                    <h3>2. Migration Çalıştır</h3>
                    <p>Veritabanındaki tüm eksik tabloları ve yeni kolonları güvenli şekilde oluşturur (<code>migrate --force</code>).</p>
                </div>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="run_command" value="migrate">
                    <button type="submit" class="btn-success">Migration Çalıştır</button>
                </form>
            </div>

            <div class="action-card">
                <div>
                    <h3>3. Temel Yetki & Admin Kurulumu</h3>
                    <p>Roller, izinler ve varsayılan yönetici kullanıcısını oluşturur (<code>RolesAndPermissionsSeeder</code> + <code>UserSeeder</code>).</p>
                </div>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="run_command" value="seed_core">
                    <button type="submit" class="btn-primary">Temel Verileri Yükle</button>
                </form>
            </div>

            <div class="action-card">
                <div>
                    <h3>4. Tüm Demo Verilerini Yükle</h3>
                    <p>İsteğe bağlı: Örnek şantiye, proje, finans ve İSG demo verilerini yükler (<code>db:seed --force</code>).</p>
                </div>
                <form method="post" onsubmit="return confirm('Tüm demo verileri veritabanına eklenecek. Onaylıyor musunuz?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="run_command" value="seed_all">
                    <button type="submit" class="btn-warning">Demo Verilerini Yükle</button>
                </form>
            </div>

            <div class="action-card">
                <div>
                    <h3>5. Önbellekleri Temizle</h3>
                    <p>Config, Route, View ve Uygulama önbelleklerini temizler (<code>optimize:clear</code>).</p>
                </div>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="run_command" value="clear_cache">
                    <button type="submit" class="btn-secondary">Cache Temizle</button>
                </form>
            </div>

            <div class="action-card">
                <div>
                    <h3>6. Storage Link & Güvenli Silme</h3>
                    <p>Yüklenen dosyalar için <code>storage:link</code> oluşturun veya işiniz bittiğinde bu aracı silin.</p>
                </div>
                <div style="display:flex;gap:8px;">
                    <form method="post" style="flex:1;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="run_command" value="storage_link">
                        <button type="submit" class="btn-secondary">Storage Link</button>
                    </form>
                    <form method="post" style="flex:1;" onsubmit="return confirm('cpanel-migrate.php dosyası sunucudan kalıcı olarak silinecek. Emin misiniz?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['cpanel_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="run_command" value="self_delete">
                        <button type="submit" class="btn-danger">Dosyayı Sil</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($commandTitle !== ''): ?>
            <div class="alert <?= $commandSuccess ? 'alert-success' : 'alert-danger' ?>">
                <strong><?= $commandSuccess ? '✅ İşlem Tamamlandı:' : '❌ Hata Oluştu:' ?></strong>
                <?= htmlspecialchars($commandTitle, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="terminal"><?= htmlspecialchars($commandOutput ?: '(Çıktı yok)', ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

    <?php endif; ?>
</div>
</body>
</html>
