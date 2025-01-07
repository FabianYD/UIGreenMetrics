<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Consumo de Energía</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            color: #2F855A;
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
        <h1>Reporte de Consumo de Energía</h1>
        <p>Fecha de generación: {{ $fecha }}</p>
    </div>

    <div class="content">
        <div class="info-group">
            <span class="label">Tipo de Energía:</span>
            <span class="value">{{ ucfirst($energy->tipo_energia) }}</span>
        </div>

        <div class="info-group">
            <span class="label">Consumo:</span>
            <span class="value">{{ number_format($energy->consumo, 2) }} {{ $energy->unidad_medida }}</span>
        </div>

        <div class="info-group">
            <span class="label">Fecha de Registro:</span>
            <span class="value">{{ $energy->fecha_registro->format('d/m/Y') }}</span>
        </div>

        <div class="info-group">
            <span class="label">Costo:</span>
            <span class="value">${{ number_format($energy->costo, 2) }}</span>
        </div>

        @if($energy->ubicacion)
        <div class="info-group">
            <span class="label">Ubicación:</span>
            <span class="value">{{ $energy->ubicacion }}</span>
        </div>
        @endif

        @if($energy->descripcion)
        <div class="info-group">
            <span class="label">Descripción:</span>
            <span class="value">{{ $energy->descripcion }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Este es un documento generado automáticamente por el sistema de UIGreenMetrics</p>
    </div>
</body>
</html>
