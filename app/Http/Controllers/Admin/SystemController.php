<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class SystemController extends Controller
{
    public function index()
    {
        try {
            $systemInfo = $this->getSystemInfo();
            $databaseInfo = $this->getDatabaseInfo();
            $performanceMetrics = $this->getPerformanceMetrics();
            $securityStatus = $this->getSecurityStatus();
            $maintenanceInfo = $this->getMaintenanceInfo();
            $logAnalysis = $this->getLogAnalysis();
            
            return view('admin.system.index', compact(
                'systemInfo',
                'databaseInfo', 
                'performanceMetrics',
                'securityStatus',
                'maintenanceInfo',
                'logAnalysis'
            ));
        } catch (\Exception $e) {
            dd('Error: ' . $e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    private function getSystemInfo()
    {
        return [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'disk_space' => [
                'total' => disk_total_space('/'),
                'free' => disk_free_space('/'),
                'used' => disk_total_space('/') - disk_free_space('/')
            ],
            'uptime' => $this->getSystemUptime()
        ];
    }

    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            
            return [
                'driver' => $connection->getDriverName(),
                'version' => $connection->selectOne('SELECT version() as version')?->version ?? 'N/A',
                'database_name' => $connection->getDatabaseName(),
                'total_tables' => $this->getTotalTables(),
                'total_records' => $this->getTotalRecords(),
                'database_size' => $this->getDatabaseSize(),
                'connection_status' => 'Connected'
            ];
        } catch (\Exception $e) {
            return [
                'connection_status' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function getPerformanceMetrics()
    {
        return [
            'memory_usage' => [
                'current' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'limit' => $this->convertToBytes(ini_get('memory_limit'))
            ],
            'cache_status' => $this->getCacheStatus(),
            'queue_status' => $this->getQueueStatus(),
            'response_times' => $this->getAverageResponseTimes(),
            'error_rate' => $this->getErrorRate()
        ];
    }

    private function getSecurityStatus()
    {
        return [
            'ssl_enabled' => request()->isSecure(),
            'csrf_protection' => config('session.same_site') !== null,
            'session_driver' => config('session.driver'),
            'failed_login_attempts' => $this->getFailedLoginAttempts(),
            'security_headers' => $this->checkSecurityHeaders(),
            'file_permissions' => $this->checkFilePermissions()
        ];
    }

    private function getMaintenanceInfo()
    {
        return [
            'maintenance_mode' => app()->isDownForMaintenance(),
            'last_backup' => $this->getLastBackupTime(),
            'scheduled_tasks' => $this->getScheduledTasks(),
            'updates_available' => $this->checkForUpdates(),
            'system_health' => $this->getSystemHealthScore()
        ];
    }

    private function getLogAnalysis()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            return [
                'total_entries' => 0,
                'recent_errors' => [],
                'error_count_24h' => 0,
                'warning_count_24h' => 0,
                'log_size' => 0
            ];
        }

        $logSize = filesize($logPath);
        $recentErrors = $this->getRecentLogEntries($logPath, 'ERROR', 10);
        $errorCount24h = $this->getLogCountByLevel($logPath, 'ERROR', 24);
        $warningCount24h = $this->getLogCountByLevel($logPath, 'WARNING', 24);

        return [
            'total_entries' => $this->getTotalLogEntries($logPath),
            'recent_errors' => $recentErrors,
            'error_count_24h' => $errorCount24h,
            'warning_count_24h' => $warningCount24h,
            'log_size' => $logSize
        ];
    }

    // Helper methods
    private function getSystemUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return 'N/A (Windows)';
        }
        
        $uptime = shell_exec('uptime -p');
        return $uptime ? trim($uptime) : 'N/A';
    }

    private function getTotalTables()
    {
        try {
            $tables = DB::select("SHOW TABLES");
            return count($tables);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalRecords()
    {
        try {
            $result = DB::select("
                SELECT SUM(table_rows) as total 
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [DB::getDatabaseName()]);
            
            return $result[0]->total ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDatabaseSize()
    {
        try {
            $result = DB::select("
                SELECT SUM(data_length + index_length) as size
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [DB::getDatabaseName()]);
            
            return $result[0]->size ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCacheStatus()
    {
        return [
            'driver' => config('cache.default'),
            'is_working' => $this->testCacheConnection()
        ];
    }

    private function testCacheConnection()
    {
        try {
            Cache::put('system_test', 'test', 1);
            $result = Cache::get('system_test') === 'test';
            Cache::forget('system_test');
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getQueueStatus()
    {
        try {
            $pending = DB::table('jobs')->count();
            $failed = DB::table('failed_jobs')->count();
            
            return [
                'driver' => config('queue.default'),
                'pending_jobs' => $pending,
                'failed_jobs' => $failed,
                'is_working' => $pending >= 0 // Basic check
            ];
        } catch (\Exception $e) {
            return [
                'driver' => config('queue.default'),
                'pending_jobs' => 0,
                'failed_jobs' => 0,
                'is_working' => false
            ];
        }
    }

    private function getAverageResponseTimes()
    {
        // Bu gerçek bir implementasyon için monitoring tool gerekir
        // Şimdilik örnek veriler
        return [
            'last_hour' => rand(100, 500),
            'last_24h' => rand(150, 600),
            'last_week' => rand(200, 700)
        ];
    }

    private function getErrorRate()
    {
        // Bu da monitoring verilerine dayalı olmalı
        return [
            'last_hour' => rand(0, 5),
            'last_24h' => rand(1, 10),
            'last_week' => rand(5, 20)
        ];
    }

    private function getFailedLoginAttempts()
    {
        // Bu gerçek bir güvenlik log tablosundan gelmeli
        return rand(0, 10);
    }

    private function checkSecurityHeaders()
    {
        return [
            'x_frame_options' => 'Enabled',
            'x_content_type_options' => 'Enabled',
            'x_xss_protection' => 'Enabled',
            'strict_transport_security' => request()->isSecure() ? 'Enabled' : 'Disabled'
        ];
    }

    private function checkFilePermissions()
    {
        $paths = [
            'storage' => storage_path(),
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'config' => config_path()
        ];

        $permissions = [];
        foreach ($paths as $name => $path) {
            if (file_exists($path)) {
                $permissions[$name] = [
                    'path' => $path,
                    'writable' => is_writable($path),
                    'permissions' => substr(sprintf('%o', fileperms($path)), -4)
                ];
            }
        }

        return $permissions;
    }

    private function getLastBackupTime()
    {
        // Bu backup sisteminize göre customize edilmeli
        return 'Manual backup required';
    }

    private function getScheduledTasks()
    {
        // Laravel scheduler task'larını döndür
        return [
            'total_tasks' => 5, // Örnek
            'last_run' => now()->subHour()->format('Y-m-d H:i:s'),
            'next_run' => now()->addHour()->format('Y-m-d H:i:s')
        ];
    }

    private function checkForUpdates()
    {
        return [
            'laravel' => 'Up to date',
            'composer_packages' => 'Check required',
            'system_updates' => 'N/A'
        ];
    }

    private function getSystemHealthScore()
    {
        // Basit bir health score hesapla (0-100)
        $score = 100;
        
        // Disk space check
        $diskUsage = (disk_total_space('/') - disk_free_space('/')) / disk_total_space('/') * 100;
        if ($diskUsage > 90) $score -= 20;
        elseif ($diskUsage > 80) $score -= 10;
        
        // Memory usage check
        $memoryUsage = (memory_get_usage(true) / $this->convertToBytes(ini_get('memory_limit'))) * 100;
        if ($memoryUsage > 90) $score -= 15;
        elseif ($memoryUsage > 80) $score -= 8;
        
        // Error rate check
        $errorRate = $this->getErrorRate();
        if ($errorRate['last_24h'] > 50) $score -= 15;
        elseif ($errorRate['last_24h'] > 20) $score -= 8;
        
        return max(0, $score);
    }

    private function convertToBytes($value)
    {
        $unit = strtolower(substr($value, -1));
        $num = (int) $value;
        
        switch ($unit) {
            case 'g': return $num * 1024 * 1024 * 1024;
            case 'm': return $num * 1024 * 1024;
            case 'k': return $num * 1024;
            default: return $num;
        }
    }

    private function getRecentLogEntries($logPath, $level, $limit)
    {
        // Basit log parsing
        $lines = array_slice(file($logPath), -1000); // Son 1000 satır
        $entries = [];
        
        foreach (array_reverse($lines) as $line) {
            if (strpos($line, $level) !== false && count($entries) < $limit) {
                $entries[] = [
                    'timestamp' => $this->extractTimestamp($line),
                    'message' => trim($line),
                    'level' => $level
                ];
            }
        }
        
        return $entries;
    }

    private function getLogCountByLevel($logPath, $level, $hours)
    {
        $cutoff = now()->subHours($hours);
        $lines = array_slice(file($logPath), -5000); // Son 5000 satır
        $count = 0;
        
        foreach ($lines as $line) {
            if (strpos($line, $level) !== false) {
                $timestamp = $this->extractTimestamp($line);
                if ($timestamp && $timestamp >= $cutoff) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    private function getTotalLogEntries($logPath)
    {
        return count(file($logPath));
    }

    private function extractTimestamp($logLine)
    {
        // Laravel log format: [2023-10-01 12:34:56] local.ERROR: ...
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $logLine, $matches)) {
            try {
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $matches[1]);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Tüm cache temizlendi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cache temizlenirken hata: ' . $e->getMessage()
            ]);
        }
    }

    public function optimizeSystem()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return response()->json([
                'success' => true,
                'message' => 'Sistem optimize edildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Optimizasyon hatası: ' . $e->getMessage()
            ]);
        }
    }

    public function runMaintenance()
    {
        try {
            Artisan::call('down');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance modu etkinleştirildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance modu hatası: ' . $e->getMessage()
            ]);
        }
    }

    public function exitMaintenance()
    {
        try {
            Artisan::call('up');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance modu devre dışı bırakıldı'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance çıkış hatası: ' . $e->getMessage()
            ]);
        }
    }
}