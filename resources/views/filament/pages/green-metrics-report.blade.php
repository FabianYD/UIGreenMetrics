<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <!-- Water Conservation Program -->
        <x-filament::section>
            <x-slot name="heading">4.1 (WR.1) Programa de Conservación de Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Total de Programas:</span>
                    <span>{{ $waterConservation['total_programas'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Programas Activos:</span>
                    <span>{{ $waterConservation['programas_activos'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Avance Promedio:</span>
                    <span>{{ number_format($waterConservation['avance_promedio'], 1) }}%</span>
                </div>
                <div class="mt-2 pt-2 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Puntaje:</span>
                        <span>{{ $waterConservation['puntaje'] }} / 300</span>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Water Recycling Program -->
        <x-filament::section>
            <x-slot name="heading">4.2 (WR.2) Programa de Reciclaje de Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Agua Reciclada:</span>
                    <span>{{ number_format($waterRecycling['total_reciclado'], 1) }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span>Consumo Total:</span>
                    <span>{{ number_format($waterRecycling['total_consumo'], 1) }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span>Porcentaje Reciclado:</span>
                    <span>{{ number_format($waterRecycling['porcentaje'], 1) }}%</span>
                </div>
                <div class="mt-2 pt-2 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Puntaje:</span>
                        <span>{{ $waterRecycling['puntaje'] }} / 300</span>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Water Efficient Appliances -->
        <x-filament::section>
            <x-slot name="heading">4.3 (WR.3) Uso de Dispositivos Eficientes</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Total Dispositivos:</span>
                    <span>{{ $efficientAppliances['total_dispositivos'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Dispositivos Eficientes:</span>
                    <span>{{ $efficientAppliances['dispositivos_eficientes'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Porcentaje Eficiente:</span>
                    <span>{{ number_format($efficientAppliances['porcentaje'], 1) }}%</span>
                </div>
                <div class="mt-2 pt-2 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Puntaje:</span>
                        <span>{{ $efficientAppliances['puntaje'] }} / 300</span>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Treated Water Consumption -->
        <x-filament::section>
            <x-slot name="heading">4.4 (WR.4) Consumo de Agua Tratada</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Agua Tratada:</span>
                    <span>{{ number_format($treatedWater['total_tratado'], 1) }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span>Consumo Total:</span>
                    <span>{{ number_format($treatedWater['total_consumo'], 1) }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span>Porcentaje Tratado:</span>
                    <span>{{ number_format($treatedWater['porcentaje'], 1) }}%</span>
                </div>
                <div class="mt-2 pt-2 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Puntaje:</span>
                        <span>{{ $treatedWater['puntaje'] }} / 300</span>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Water Pollution Control -->
        <x-filament::section>
            <x-slot name="heading">4.5 (WR.5) Control de Contaminación del Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Estado:</span>
                    <span>
                        @switch($pollutionControl['estado'])
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
                @if($pollutionControl['fecha_inicio'])
                <div class="flex justify-between">
                    <span>Fecha de Inicio:</span>
                    <span>{{ \Carbon\Carbon::parse($pollutionControl['fecha_inicio'])->format('d/m/Y') }}</span>
                </div>
                @endif
                <div class="mt-2 pt-2 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Puntaje:</span>
                        <span>{{ $pollutionControl['puntaje'] }} / 300</span>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Water Usage Reduction -->
        <x-filament::section>
            <x-slot name="heading">4.6 (WR.6) Reducción del Consumo de Agua</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Consumo Actual:</span>
                    <span>{{ number_format($waterUsageReduction['consumo_actual'], 1) }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span>Consumo Anterior:</span>
                    <span>{{ number_format($waterUsageReduction['consumo_anterior'], 1) }} m³</span>
                </div>
                <div class="flex justify-between">
                    <span>Reducción:</span>
                    <span>{{ number_format($waterUsageReduction['reduccion'], 1) }}%</span>
                </div>
                <div class="mt-2 pt-2 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Puntaje:</span>
                        <span>{{ $waterUsageReduction['puntaje'] }} / 300</span>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>

    <!-- Total Score Section -->
    <div class="mt-8">
        <x-filament::section>
            <x-slot name="heading">Puntaje Total - Variable Agua</x-slot>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>WR.1 Conservación:</span>
                            <span>{{ $waterConservation['puntaje'] }} / 300</span>
                        </div>
                        <div class="flex justify-between">
                            <span>WR.2 Reciclaje:</span>
                            <span>{{ $waterRecycling['puntaje'] }} / 300</span>
                        </div>
                        <div class="flex justify-between">
                            <span>WR.3 Dispositivos:</span>
                            <span>{{ $efficientAppliances['puntaje'] }} / 300</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>WR.4 Agua Tratada:</span>
                            <span>{{ $treatedWater['puntaje'] }} / 300</span>
                        </div>
                        <div class="flex justify-between">
                            <span>WR.5 Control:</span>
                            <span>{{ $pollutionControl['puntaje'] }} / 300</span>
                        </div>
                        <div class="flex justify-between">
                            <span>WR.6 Reducción:</span>
                            <span>{{ $waterUsageReduction['puntaje'] }} / 300</span>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Puntaje Total:</span>
                        <span>{{ $totalScore }} / 1800</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        Porcentaje alcanzado: {{ number_format(($totalScore / 1800) * 100, 1) }}%
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>

    <div class="mt-4">
        <x-filament::section>
            <x-slot name="heading">Recomendaciones</x-slot>
            <div class="prose max-w-none">
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    @if($waterConservation['puntaje'] < 200)
                        <li>Implementar más programas de conservación de agua</li>
                    @endif
                    
                    @if($waterRecycling['puntaje'] < 200)
                        <li>Aumentar el porcentaje de agua reciclada</li>
                    @endif
                    
                    @if($efficientAppliances['puntaje'] < 200)
                        <li>Incrementar la instalación de dispositivos eficientes</li>
                    @endif
                    
                    @if($treatedWater['puntaje'] < 200)
                        <li>Mejorar el tratamiento del agua consumida</li>
                    @endif
                    
                    @if($pollutionControl['puntaje'] < 200)
                        <li>Avanzar en la implementación del control de contaminación</li>
                    @endif
                    
                    @if($waterUsageReduction['puntaje'] < 200)
                        <li>Implementar medidas para reducir el consumo de agua</li>
                    @endif
                </ul>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
