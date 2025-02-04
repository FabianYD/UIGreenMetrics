<x-filament-panels::page class="filament-dashboard-page">
    <div class="space-y-6" x-data="{ windowHeight: window.innerHeight }" x-init="window.addEventListener('resize', () => windowHeight = window.innerHeight)">
        <div class="p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
            <h2 class="text-2xl font-bold mb-4 text-emerald-600 dark:text-emerald-400">
                Sistema de Infraestructura y Desechos
            </h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Accede al sistema de gestión de infraestructura y manejo de desechos de la universidad.
            </p>
        </div>

        <div class="relative overflow-hidden rounded-lg shadow-lg bg-white dark:bg-gray-800" 
             x-data="{ loading: true }"
             x-init="$nextTick(() => { setTimeout(() => loading = false, 1000) })"
             :style="{ 'height': `${windowHeight - 200}px` }">
            <!-- Loader -->
            <div x-show="loading" 
                 class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800 z-50">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-emerald-500"></div>
            </div>

            <!-- iframe container with animation -->
            <div x-show="!loading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="w-full h-full">
                <iframe src="https://gmwsi.azurewebsites.net/"
                        class="w-full h-full border-0"
                        style="transition: all 0.3s ease-in-out;">
                </iframe>
            </div>
        </div>
    </div>
</x-filament-panels::page>
