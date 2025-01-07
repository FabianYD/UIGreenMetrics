<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Consumo de Agua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            color: #2B6CB0;
            margin-bottom: 30px;
        }
        .content {
            margin: 20px;
        }
        .info-group {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #2D3748;
        }
        .value {
            color: #4A5568;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Consumo de Agua</h1>
        <p>Fecha de generación: {{ $fecha }}</p>
    </div>

    <div class="content">
        <div class="info-group">
            <span class="label">ID del Medidor:</span>
            <span class="value">{{ $water->medidor_id }}</span>
        </div>

        <div class="info-group">
            <span class="label">Tipo de Consumo:</span>
            <span class="value">{{ ucfirst($water->tipo_consumo) }}</span>
        </div>

        <div class="info-group">
            <span class="label">Consumo Total:</span>
            <span class="value">{{ number_format($water->consumo_total, 2) }} m³</span>
        </div>

        <div class="info-group">
            <span class="label">Fecha de Pago:</span>
            <span class="value">{{ $water->fecha_pago->format('d/m/Y') }}</span>
        </div>

        @if($water->ubicacion)
        <div class="info-group">
            <span class="label">Ubicación:</span>
            <span class="value">{{ $water->ubicacion }}</span>
        </div>
        @endif

        @if($water->descripcion)
        <div class="info-group">
            <span class="label">Descripción:</span>
            <span class="value">{{ $water->descripcion }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Este es un documento generado automáticamente por el sistema de UIGreenMetrics</p>
    </div>
</body>
</html>
