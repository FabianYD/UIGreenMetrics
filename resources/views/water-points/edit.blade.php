@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Editar Tratamiento</h1>
    <form action="{{ route('water-points.update', $tratamiento) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="tragua_total" class="form-label">Total Tratado</label>
            <input type="number" step="0.01" name="tragua_total" id="tragua_total" class="form-control" value="{{ $tratamiento->tragua_total }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('water-points.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection