<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <!-- Water Conservation Program -->
        <x-filament::section>
            <x-slot name="heading">4.1 (WR.1) Programa de Conservación de Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Programas Activos:</span>
                    <span>{{ $waterConservation['total'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Avance Promedio:</span>
                    <span>{{ number_format($waterConservation['avance_promedio'], 1) }}%</span>
                </div>
            </div>
        </x-filament::section>

        <!-- Water Recycling Program -->
        <x-filament::section>
            <x-slot name="heading">4.2 (WR.2) Programa de Reciclaje de Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Porcentaje de Agua Reciclada:</span>
                    <span>{{ number_format($waterRecycling, 1) }}%</span>
                </div>
                <div class="text-sm text-gray-500">
                    @if($waterRecycling > 50)
                        Estado: Excelente
                    @elseif($waterRecycling > 25)
                        Estado: Bueno
                    @else
                        Estado: Necesita Mejorar
                    @endif
                </div>
            </div>
        </x-filament::section>

        <!-- Water Efficient Appliances -->
        <x-filament::section>
            <x-slot name="heading">4.3 (WR.3) Uso de Dispositivos Eficientes</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Dispositivos Eficientes:</span>
                    <span>{{ number_format($efficientAppliances, 1) }}%</span>
                </div>
                <div class="text-sm text-gray-500">
                    @if($efficientAppliances > 60)
                        Estado: Excelente
                    @elseif($efficientAppliances > 40)
                        Estado: Bueno
                    @else
                        Estado: Necesita Mejorar
                    @endif
                </div>
            </div>
        </x-filament::section>

        <!-- Treated Water Consumption -->
        <x-filament::section>
            <x-slot name="heading">4.4 (WR.4) Consumo de Agua Tratada</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Agua Tratada Consumida:</span>
                    <span>{{ number_format($treatedWater, 1) }}%</span>
                </div>
                <div class="text-sm text-gray-500">
                    @if($treatedWater > 75)
                        Estado: Excelente
                    @elseif($treatedWater > 50)
                        Estado: Bueno
                    @else
                        Estado: Necesita Mejorar
                    @endif
                </div>
            </div>
        </x-filament::section>

        <!-- Water Pollution Control -->
        <x-filament::section>
            <x-slot name="heading">4.5 (WR.5) Control de Contaminación del Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Estado Actual:</span>
                    <span>
                        @switch($pollutionControl)
                            @case('implementacion_completa')
                                Implementación Completa
                                @break
                            @case('implementacion_temprana')
                                Implementación Temprana
                                @break
                            @case('planificacion')
                                En Planificación
                                @break
                            @default
                                Sin Implementar
                        @endswitch
                    </span>
                </div>
            </div>
        </x-filament::section>
    </div>

    <div class="mt-4">
        <x-filament::section>
            <x-slot name="heading">4.6 Planificación e Implementación</x-slot>
            <div class="prose max-w-none">
                <p class="text-gray-600">
                    Este reporte proporciona una visión general del estado actual de la gestión del agua en la institución.
                    Los datos mostrados se actualizan automáticamente basándose en la información registrada en el sistema.
                </p>
                
                <h3 class="text-lg font-medium text-gray-900 mt-4">Recomendaciones</h3>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    @if($waterRecycling < 25)
                        <li>Implementar más programas de reciclaje de agua</li>
                    @endif
                    
                    @if($efficientAppliances < 40)
                        <li>Aumentar la instalación de dispositivos eficientes</li>
                    @endif
                    
                    @if($treatedWater < 50)
                        <li>Mejorar el tratamiento y reutilización del agua</li>
                    @endif
                    
                    @if($pollutionControl === 'sin_implementar')
                        <li>Iniciar la implementación de controles de contaminación</li>
                    @endif
                </ul>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
