@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Editar Punto de Recolección</h1>
            <a href="{{ route('water-points.index') }}" class="text-blue-500 hover:text-blue-700">
                Volver al listado
            </a>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form action="{{ route('water-points.update', $waterPoint) }}" method="POST">
                @method('PUT')
                @include('water-points.form')

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Actualizar Punto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
