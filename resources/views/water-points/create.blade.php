@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Nuevo Tratamiento</h1>
    <form action="{{ route('water-points.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="consag_id" class="form-label">ID Consumo Agua</label>
            <input type="number" name="consag_id" id="consag_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tipotra_cod" class="form-label">Tipo de Tratamiento</label>
            <select name="tipotra_cod" id="tipotra_cod" class="form-control" required>
                @foreach(App\Models\TipoTratamiento::all() as $tipo)
                    <option value="{{ $tipo->tipotra_cod }}">{{ $tipo->tipotra_nombres }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tragua_total" class="form-label">Total Tratado</label>
            <input type="number" step="0.01" name="tragua_total" id="tragua_total" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('water-points.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection