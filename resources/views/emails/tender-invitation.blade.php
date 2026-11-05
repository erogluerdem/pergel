<div style="font-family: Arial, sans-serif;">
    <h3>İhale Daveti: {{ $package->name }}</h3>
    <p>Merhaba,</p>
    <p>{{ optional($package->project)->name }} projesi için oluşturulan "{{ $package->name }}" ihale paketine davet edildiniz.</p>
    <p>Pakete erişmek ve teklif vermek için aşağıdaki bağlantıyı kullanabilirsiniz. Bu bağlantı sınırlı süre için geçerlidir.</p>
    <p><a href="{{ $accessUrl }}">İhale Paketine Git</a></p>
    <p>Saygılarımızla,<br/>Proje Ekibi</p>
</div>
