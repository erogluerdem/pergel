<div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
    <h2>Fatura: {{ $invoice->no }}</h2>
    <p>{{ $body }}</p>

    <h4>Fatura Özeti</h4>
    <p>Tarih: {{ optional($invoice->date)->format('d.m.Y') }}</p>
    <p>Tutar: ₺{{ number_format($invoice->amount, 2, ',', '.') }}</p>

    <hr>
    <p>Bu e-posta sistem tarafından otomatik gönderilmiştir.</p>
</div>
