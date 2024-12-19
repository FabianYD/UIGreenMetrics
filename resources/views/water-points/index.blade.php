@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Tratamientos de Agua</h1>
    <div class="text-end mb-3">
        <a href="{{ route('water-points.create') }}" class="btn btn-primary">Nuevo Tratamiento</a>
        <form action="{{ route('water-points.calcular') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Calcular Sostenibilidad</button>
        </form>
    </div>
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
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tratamientos as $tratamiento)
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
                    <td>Acción</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection