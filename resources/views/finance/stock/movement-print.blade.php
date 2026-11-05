<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Hareketi - #{{ $movement->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        
        .print-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #4e73df;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #4e73df;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .movement-info {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: bold;
            color: #5a5c69;
            flex: 1;
        }
        
        .info-value {
            flex: 2;
            text-align: right;
        }
        
        .movement-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .type-in {
            background: #1cc88a;
            color: white;
        }
        
        .type-out {
            background: #e74a3b;
            color: white;
        }
        
        .type-adjustment {
            background: #f6c23e;
            color: white;
        }
        
        .type-transfer {
            background: #36b9cc;
            color: white;
        }
        
        .financial-summary {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #1cc88a;
            margin: 20px 0;
        }
        
        .financial-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .financial-row.total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #1cc88a;
            padding-top: 10px;
            margin-top: 15px;
        }
        
        .description-section {
            margin-top: 20px;
            padding: 20px;
            background: #fff8e1;
            border-radius: 8px;
            border-left: 5px solid #f6c23e;
        }
        
        .description-section h3 {
            color: #f6c23e;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e3e6f0;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 200px;
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .print-container {
                margin: 0;
                padding: 20px;
                box-shadow: none;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <h1>STOK HAREKETİ BELGESİ</h1>
            <p>Hareket No: #{{ $movement->id }} | Tarih: {{ $movement->created_at->format('d.m.Y H:i') }}</p>
        </div>
        
        <!-- Movement Information -->
        <div class="movement-info">
            <div class="info-row">
                <div class="info-label">Hareket Tipi:</div>
                <div class="info-value">
                    <span class="movement-type type-{{ $movement->type }}">
                        @switch($movement->type)
                            @case('in')
                                STOK GİRİŞİ
                                @break
                            @case('out')
                                STOK ÇIKIŞI
                                @break
                            @case('adjustment')
                                STOK DÜZELTMESİ
                                @break
                            @case('transfer')
                                STOK TRANSFERİ
                                @break
                        @endswitch
                    </span>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Ürün Kodu:</div>
                <div class="info-value">{{ $movement->stockCard->code ?? 'N/A' }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Ürün Adı:</div>
                <div class="info-value">{{ $movement->stockCard->name ?? 'N/A' }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Kategori:</div>
                <div class="info-value">{{ $movement->stockCard->category ?? '-' }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Birim:</div>
                <div class="info-value">{{ $movement->stockCard->unit ?? 'Adet' }}</div>
            </div>
            
            @if($movement->reference_no)
            <div class="info-row">
                <div class="info-label">Referans No:</div>
                <div class="info-value">{{ $movement->reference_no }}</div>
            </div>
            @endif
            
            <div class="info-row">
                <div class="info-label">İşlem Yapan:</div>
                <div class="info-value">{{ $movement->user->name ?? 'N/A' }}</div>
            </div>
        </div>
        
        <!-- Financial Summary -->
        <div class="financial-summary">
            <div class="financial-row">
                <span>Miktar:</span>
                <span>{{ number_format($movement->quantity, 2) }} {{ $movement->stockCard->unit ?? 'Adet' }}</span>
            </div>
            
            <div class="financial-row">
                <span>Birim Fiyat:</span>
                <span>₺{{ number_format($movement->unit_price, 2) }}</span>
            </div>
            
            <div class="financial-row total">
                <span>TOPLAM TUTAR:</span>
                <span>{{ $movement->type == 'in' ? '+' : '-' }}₺{{ number_format($movement->total_amount, 2) }}</span>
            </div>
        </div>
        
        <!-- Description -->
        @if($movement->description)
        <div class="description-section">
            <h3>Açıklama</h3>
            <p>{{ $movement->description }}</p>
        </div>
        @endif
        
        <!-- Stock Impact -->
        <div style="background: #f0f8ff; padding: 15px; border-radius: 8px; border-left: 5px solid #36b9cc; margin: 20px 0;">
            <strong>Stok Etkisi:</strong>
            @if($movement->type == 'in')
                Bu hareket sonrasında <strong>{{ $movement->stockCard->name }}</strong> ürününün stok miktarı 
                <strong style="color: #1cc88a;">+{{ number_format($movement->quantity, 2) }}</strong> artmıştır.
            @elseif($movement->type == 'out')
                Bu hareket sonrasında <strong>{{ $movement->stockCard->name }}</strong> ürününün stok miktarı 
                <strong style="color: #e74a3b;">-{{ number_format($movement->quantity, 2) }}</strong> azalmıştır.
            @elseif($movement->type == 'adjustment')
                Bu hareket ile <strong>{{ $movement->stockCard->name }}</strong> ürününün stok miktarı 
                <strong style="color: #f6c23e;">{{ number_format($movement->quantity, 2) }}</strong> olarak düzeltilmiştir.
            @endif
        </div>
        
        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                İşlem Yapan<br>
                <strong>{{ $movement->user->name ?? 'N/A' }}</strong>
            </div>
            <div class="signature-box">
                Onaylayan<br>
                <strong>________________</strong>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Bu belge {{ $movement->created_at->format('d.m.Y H:i:s') }} tarihinde sistem tarafından otomatik olarak oluşturulmuştur.</p>
            <p>Belge No: {{ $movement->id }} | Yazdırma Tarihi: {{ now()->format('d.m.Y H:i:s') }}</p>
        </div>
        
        <!-- Print Button (hidden when printing) -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="background: #4e73df; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px;">
                🖨️ Yazdır
            </button>
            <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
                ❌ Kapat
            </button>
        </div>
    </div>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>