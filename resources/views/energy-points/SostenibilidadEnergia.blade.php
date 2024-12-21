@extends('layouts.appEnergy')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Sostenibilidad Energética</h1>

    {{-- Primera Tabla --}}
    <h2 class="text-center mb-3">Generación Energética</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Universidad</th>
                <th>Campus</th>
                <th>Facultad</th>
                <th>Energía Generada (kWh)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($generaciones as $generacion)
                <tr>
                    {{-- Nombre de la universidad --}}
                    <td>{{ $generacion->facultad->campus->universidad->uni_nombres ?? 'N/A' }}</td>
                    
                    {{-- Nombre del campus --}}
                    <td>{{ $generacion->facultad->campus->campus_nombres ?? 'N/A' }}</td>
                    
                    {{-- Nombre de la facultad --}}
                    <td>{{ $generacion->facultad->facu_nombre ?? 'N/A' }}</td>
                    
                    {{-- Cantidad de energía generada --}}
                    <td>{{ $generacion-> genene_total ?? 'N/A' }} kWh</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Segunda Tabla --}}
    <h2 class="text-center mb-3">Consumo Energético</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Universidad</th>
                <th>Campus</th>
                <th>Código Medidor</th>
                <th>Tipo de Energía</th>
                <th>Consumo de Energía</th>
                <th>Unidad de Medida</th>
                <th>Sostenibilidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumos as $consumo)
                <tr>
                    <td>{{ $consumo->medidorEnergia->campus->universidad->uni_nombres ?? 'N/A' }}</td>
                    <td>{{ $consumo->medidorEnergia->campus->campus_nombres ?? 'N/A' }}</td>
                    <td>{{ $consumo->medidorEnergia->medene_id ?? 'N/A' }}</td>
                    <td>{{ $consumo->tipoEnergia->tipoene_nombres ?? 'N/A' }}</td>
                    <td>{{ $consumo->consene_total ?? 'N/A' }}</td>
                    <td>{{ $consumo->unidadMedida->medene_nombre ?? 'N/A' }}</td>
                    <td>{{ $consumo->sostenibilidadene }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
