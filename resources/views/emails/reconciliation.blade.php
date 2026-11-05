<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mutabakat Mektubu</title>
    <style>body{font-family:DejaVu Sans, Arial, sans-serif; font-size:14px;} .header{margin-bottom:20px;} .table{width:100%;border-collapse:collapse;} .table th,.table td{border:1px solid #ddd;padding:8px;}</style>
</head>
<body>
    <div class="header">
        <h3>Mutabakat Mektubu</h3>
        <p>Müşteri: {{ $customer->name ?? '' }}</p>
    </div>

    <div class="content">
        {!! $bodyHtml !!}
    </div>

</body>
</html>
