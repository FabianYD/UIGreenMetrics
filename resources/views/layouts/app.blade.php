<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Reciclaje de Agua</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-xl font-bold text-blue-600">
                            Sistema de Reciclaje
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#00b0f0'
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#00b0f0'
                });
            </script>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white shadow mt-8 py-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center text-gray-500 text-sm">
                {{ date('Y') }} Sistema de Gestión de Reciclaje de Agua. Todos los derechos reservados.
            </div>
        </div>
    </footer>
</body>
</html>
