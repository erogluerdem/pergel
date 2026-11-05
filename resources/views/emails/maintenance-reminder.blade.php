<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bakım Hatırlatması</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb;">Bakım Hatırlatması</h1>

        <p>Merhaba,</p>

        <p><strong>{{ $asset->name }}</strong> adlı varlığın bakım zamanı yaklaşıyor.</p>

        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #1f2937;">Varlık Bilgileri:</h3>
            <ul style="list-style: none; padding: 0;">
                <li><strong>Varlık:</strong> {{ $asset->name }}</li>
                <li><strong>Konum:</strong> {{ is_array($asset->location) || is_object($asset->location) ? json_encode((array)$asset->location, JSON_UNESCAPED_UNICODE) : ($asset->location ?? 'Belirtilmemiş') }}</li>
                <li><strong>Kalan Gün:</strong> {{ $daysUntilMaintenance }} gün</li>
                <li><strong>Sonraki Bakım Tarihi:</strong> {{ $asset->next_maintenance_date ? $asset->next_maintenance_date->format('d.m.Y') : 'Belirtilmemiş' }}</li>
            </ul>
        </div>

        <p>Lütfen bakım planınızı yapın ve gerekli hazırlıkları tamamlayın.</p>

        <div style="background-color: #fef3c7; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #f59e0b;">
            <strong>Önemli:</strong> Düzenli bakım, varlığın ömrünü uzatır ve beklenmedik arızaları önler.
        </div>

        <p>Saygılarımla,<br>
        Tesis Yönetim Sistemi</p>
    </div>
</body>
</html>
