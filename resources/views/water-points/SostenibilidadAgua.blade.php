@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Sistema de Reciclaje de Agua</h1>

    {{-- Primera tabla --}}
    <h2 class="text-center mb-4">Reutilización de Aguas</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Universidad</th>
                <th>Campus</th>
                <th>Código Medidor</th>
                <th>Agua Consumida</th>
                <th>Agua Reciclada</th>
                <th>Detalles</th>
                <th>Sostenibilidad (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reutilizaciones as $reutilizacion)
    <tr>
        <td>{{ $reutilizacion->consumoAgua->medidorAgua->campus->universidad->uni_nombres }}</td>
        <td>{{ $reutilizacion->consumoAgua->medidorAgua->campus->campus_nombres }}</td>
        <td>{{ $reutilizacion->consumoAgua->medidorAgua->medag_id }}</td>
        <td>{{ $reutilizacion->consumoAgua->consag_total }}</td>
        <td>{{ $reutilizacion->reuag_cantidad }}</td>
        <td>{{ $reutilizacion->reuag_detalle }}</td>
        <td>{{ $reutilizacion->reuag_sostenibilidad }}</td>
    </tr>
@endforeach
        </tbody>
    </table>

    {{-- Botón para refrescar la página --}}
    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary" onclick="location.reload()">Actualizar Datos</button>
    </div>

    {{-- Segunda tabla --}}
    <h2 class="text-center mt-5 mb-4">Tratamientos de Agua</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Universidad</th>
                <th>Campus</th>
                <th>Código Medidor</th>
                <th>Agua Consumida</th>
                <th>Tipo de Tratamiento</th>
                <th>Total Tratado</th>
                <th>Cantidad Sostenible</th>
                <th>Eficiencia (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tratamientos as $tratamiento)
                <tr>
                    <td>{{ $tratamiento->consumoAgua->medidorAgua->campus->universidad->uni_nombres }}</td>
                    <td>{{ $tratamiento->consumoAgua->medidorAgua->campus->campus_nombres }}</td>
                    <td>{{ $tratamiento->consumoAgua->medidorAgua->medag_id }}</td>
                    <td>
                        {{ $tratamiento->consumoAgua->consag_total }}
                        {{ $tratamiento->consumoAgua->unidadMedida->medidaagu_nombre }}
                    </td>
                    <td>{{ $tratamiento->tipoTratamiento->tipotra_nombres }}</td>
                    <td>{{ $tratamiento->tragua_total }}</td>
                    <td>{{ $tratamiento->cantidad_sostenible }}</td>
                    <td>{{ $tratamiento->eficiencia }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
