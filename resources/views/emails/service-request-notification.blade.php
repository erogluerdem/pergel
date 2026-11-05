<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yeni Servis Talebi</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #dc2626;">Yeni Servis Talebi</h1>

        <p>Merhaba,</p>

        <p>Yeni bir servis talebi oluşturuldu. Lütfen aşağıdaki detayları inceleyin:</p>

        <div style="background-color: #fef2f2; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc2626;">
            <h3 style="margin-top: 0; color: #1f2937;">Talebi Detayları:</h3>
            <ul style="list-style: none; padding: 0;">
                <li><strong>Başlık:</strong> {{ $serviceRequest->title }}</li>
                <li><strong>Açıklama:</strong> {{ $serviceRequest->description }}</li>
                <li><strong>Öncelik:</strong>
                    @if($serviceRequest->priority == 'low')
                        <span style="color: #16a34a;">Düşük</span>
                    @elseif($serviceRequest->priority == 'medium')
                        <span style="color: #ca8a04;">Orta</span>
                    @elseif($serviceRequest->priority == 'high')
                        <span style="color: #dc2626;">Yüksek</span>
                    @else
                        <span style="color: #6b7280;">{{ $serviceRequest->priority }}</span>
                    @endif
                </li>
                <li><strong>Durum:</strong> {{ $serviceRequest->status }}</li>
                <li><strong>Oluşturulma Tarihi:</strong> {{ $serviceRequest->created_at->format('d.m.Y H:i') }}</li>
                @if($serviceRequest->asset)
                <li><strong>İlgili Varlık:</strong> {{ $serviceRequest->asset->name }}</li>
                @endif
                @if($serviceRequest->requested_by)
                <li><strong>Talep Eden:</strong> {{ $serviceRequest->requested_by }}</li>
                @endif
            </ul>
        </div>

        <p>Lütfen bu talebi en kısa sürede değerlendirin ve gerekli işlemleri başlatın.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/facility/service-requests/' . $serviceRequest->id) }}"
               style="background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Talebi Görüntüle
            </a>
        </div>

        <p>Saygılarımla,<br>
        Tesis Yönetim Sistemi</p>
    </div>
</body>
</html>
