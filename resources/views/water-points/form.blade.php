@csrf

<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $waterPoint->nombre ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('nombre')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
        <input type="text" name="ubicacion" id="ubicacion" value="{{ old('ubicacion', $waterPoint->ubicacion ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('ubicacion')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="capacidad" class="block text-sm font-medium text-gray-700">Capacidad (L)</label>
        <input type="number" step="0.01" name="capacidad" id="capacidad" value="{{ old('capacidad', $waterPoint->capacidad ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('capacidad')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="agua_tratada" class="block text-sm font-medium text-gray-700">Agua Tratada (L)</label>
        <input type="number" step="0.01" name="agua_tratada" id="agua_tratada" value="{{ old('agua_tratada', $waterPoint->agua_tratada ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('agua_tratada')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="agua_reciclada" class="block text-sm font-medium text-gray-700">Agua Reciclada (L)</label>
        <input type="number" step="0.01" name="agua_reciclada" id="agua_reciclada" value="{{ old('agua_reciclada', $waterPoint->agua_reciclada ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('agua_reciclada')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="agua_reutilizada" class="block text-sm font-medium text-gray-700">Agua Reutilizada (L)</label>
        <input type="number" step="0.01" name="agua_reutilizada" id="agua_reutilizada" value="{{ old('agua_reutilizada', $waterPoint->agua_reutilizada ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('agua_reutilizada')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
        <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="activo" {{ (old('estado', $waterPoint->estado ?? '') === 'activo') ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ (old('estado', $waterPoint->estado ?? '') === 'inactivo') ? 'selected' : '' }}>Inactivo</option>
        </select>
        @error('estado')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
