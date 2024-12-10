@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-[#00b0f0] py-4">
        <h1 class="text-2xl font-bold text-white text-center">Sistema de Gestión de Reciclaje de Agua</h1>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Puntos de Recolección -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-lg shadow">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Puntos de Recolección</h2>
                        <a href="{{ route('water-points.create') }}" 
                           class="bg-[#00b0f0] hover:bg-[#0099d6] text-white px-4 py-2 rounded-md transition-colors">
                            Nuevo Punto
                        </a>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">NOMBRE</th>
                                        <th class="px-4 py-3">UBICACIÓN</th>
                                        <th class="px-4 py-3">CAPACIDAD</th>
                                        <th class="px-4 py-3">ESTADO</th>
                                        <th class="px-4 py-3">ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($points as $point)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $point->nombre }}</td>
                                        <td class="px-4 py-3">{{ $point->ubicacion }}</td>
                                        <td class="px-4 py-3">
                                            <div class="space-y-1">
                                                <div class="text-sm">Agua Tratada: {{ $point->agua_tratada }} L</div>
                                                <div class="text-sm">Agua Reciclada: {{ $point->agua_reciclada }} L</div>
                                                <div class="text-sm">Agua Reutilizada: {{ $point->agua_reutilizada }} L</div>
                                                <div class="w-full bg-gray-200 h-1 rounded-full overflow-hidden">
                                                    <div class="bg-[#00b0f0] h-full" style="width: {{ $point->eficiencia }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-500">Eficiencia: {{ number_format($point->eficiencia, 1) }}%</div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $point->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $point->estado }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('water-points.edit', $point) }}" 
                                                   class="text-[#00b0f0] hover:text-[#0099d6] font-medium">
                                                    Editar
                                                </a>
                                                <form action="{{ route('water-points.destroy', $point) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 font-medium">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas y Otros -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Porcentaje de Reciclaje -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Porcentaje de Reciclaje</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-center text-gray-600">Distribución del Agua</h3>
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleChart('doughnut')" 
                                        class="px-3 py-1 text-sm rounded bg-[#00b0f0] text-white hover:bg-[#0099d6]">
                                    Dona
                                </button>
                                <button onclick="toggleChart('bar')" 
                                        class="px-3 py-1 text-sm rounded bg-[#00b0f0] text-white hover:bg-[#0099d6]">
                                    Barras
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-center mb-8">
                            <div class="w-64 h-64">
                                <canvas id="waterDistributionChart"></canvas>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-[#00b0f0]">{{ $statistics['recycled_percentage'] }}%</div>
                                <div class="text-sm text-gray-500">Agua Reciclada</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-500">{{ $statistics['reused_percentage'] }}%</div>
                                <div class="text-sm text-gray-500">Agua Reutilizada</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-red-500">{{ $statistics['not_recycled_percentage'] }}%</div>
                                <div class="text-sm text-gray-500">Agua No Reciclada</div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <span class="text-sm text-gray-500">Total: {{ $statistics['total_percentage'] }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Alertas -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Alertas</h2>
                    </div>
                    <div class="p-4">
                        <!-- Contenido de alertas -->
                    </div>
                </div>

                <!-- Reportes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Reportes</h2>
                        <button class="bg-[#00b0f0] hover:bg-[#0099d6] text-white px-4 py-2 rounded-md transition-colors">
                            Generar Reporte
                        </button>
                    </div>
                    <div class="p-4">
                        <!-- Contenido de reportes -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentChart = null;

function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Desea eliminar este punto de recolección?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00b0f0',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
    return false;
}

function toggleChart(type) {
    if (currentChart) {
        currentChart.destroy();
    }
    
    const ctx = document.getElementById('waterDistributionChart').getContext('2d');
    const config = type === 'doughnut' ? createDoughnutChart() : createBarChart();
    currentChart = new Chart(ctx, config);
}

function createDoughnutChart() {
    return {
        type: 'doughnut',
        data: {
            labels: ['Agua Reciclada', 'Agua Reutilizada', 'Agua No Reciclada'],
            datasets: [{
                data: [
                    {{ $statistics['recycled_percentage'] }},
                    {{ $statistics['reused_percentage'] }},
                    {{ $statistics['not_recycled_percentage'] }}
                ],
                backgroundColor: [
                    '#00b0f0',
                    '#10B981',
                    '#EF4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            cutout: '75%'
        }
    };
}

function createBarChart() {
    const data = [
        {{ $statistics['recycled_percentage'] }},
        {{ $statistics['reused_percentage'] }},
        {{ $statistics['not_recycled_percentage'] }}
    ];
    
    return {
        type: 'bar',
        data: {
            labels: ['Agua Reciclada', 'Agua Reutilizada', 'Agua No Reciclada'],
            datasets: [{
                data: data,
                backgroundColor: [
                    '#00b0f0',
                    '#10B981',
                    '#EF4444'
                ],
                borderWidth: 0,
                borderRadius: 6,
                barThickness: 40,
                maxBarThickness: 50
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: true
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    };
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('waterDistributionChart').getContext('2d');
    currentChart = new Chart(ctx, createDoughnutChart());
});
</script>
@endpush
@endsection
