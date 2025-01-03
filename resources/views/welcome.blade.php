<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoMetric - UI GreenMetric</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <script>
            tailwind.config = {
                darkMode: 'media',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Figtree', 'sans-serif'],
                        },
                    },
                },
            }
        </script>
        <style>
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
            .floating {
                animation: float 3s ease-in-out infinite;
            }
            .rotating {
                animation: rotate 20s linear infinite;
            }
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900 dark:to-emerald-900 overflow-hidden">
            <!-- Navbar -->
            <nav class="absolute top-0 w-full p-4 z-50">
                <div class="container mx-auto flex justify-between items-center">
                    <div class="text-green-800 dark:text-green-300 text-xl font-bold animate__animated animate__fadeIn">
                        EcoMetric
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/admin') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-lg animate__animated animate__fadeIn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Panel de Control
                            </a>
                        @else
                            <a href="{{ url('/admin/login') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-lg animate__animated animate__fadeIn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Iniciar Sesión
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="rotating absolute -right-16 -top-16 w-64 h-64 bg-green-400/10 rounded-full blur-3xl"></div>
                <div class="floating absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl"></div>
                <svg class="floating absolute right-10 top-1/4 w-20 h-20 text-green-500/20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zm0-12c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                </svg>
                <svg class="floating absolute left-1/4 bottom-1/4 w-16 h-16 text-emerald-500/20" style="animation-delay: -1s;" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17 8h3v12h1v1h-4v-1h1v-3h-4l-1.5 3H14v1h-4v-1h1l6-12zm1 1l-3.5 7H18V9zM6 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm3 7h3v12h1v1H9v-1h1v-3H6l-1.5 3H6v1H2v-1h1l6-12zm1 1L6.5 17H10V10z"/>
                </svg>
            </div>

            <!-- Main Content -->
            <div class="relative min-h-screen flex flex-col items-center justify-center">
                <div class="text-center px-6 py-12 max-w-4xl animate__animated animate__fadeInUp">
                    <h1 class="text-5xl md:text-6xl font-bold text-green-800 dark:text-green-300 mb-4">
                        EcoMetric
                    </h1>
                    <p class="text-xl md:text-2xl text-green-700 dark:text-green-200 mb-8 animate__animated animate__fadeIn animate__delay-1s">
                        Evaluando el presente, asegurando el futuro.
                    </p>
                    <p class="text-lg text-green-600 dark:text-green-300 mb-12 max-w-2xl mx-auto animate__animated animate__fadeIn animate__delay-2s">
                        Bienvenido a la plataforma líder en evaluación y seguimiento de métricas de sostenibilidad universitaria.
                    </p>
                    
                    <div class="flex justify-center space-x-4 animate__animated animate__fadeIn animate__delay-3s">
                        @auth
                            <a href="{{ url('/admin') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                                Panel de Control
                            </a>
                        @else
                            <a href="{{ url('/admin/login') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                                Iniciar Sesión
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Animated Icons -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto px-6 mb-12 animate__animated animate__fadeIn animate__delay-4s">
                    <div class="text-center">
                        <div class="floating inline-block mb-4">
                            <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-300">Sostenibilidad Global</h3>
                    </div>
                    <div class="text-center">
                        <div class="floating inline-block mb-4" style="animation-delay: -1s;">
                            <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-300">Métricas Precisas</h3>
                    </div>
                    <div class="text-center">
                        <div class="floating inline-block mb-4" style="animation-delay: -2s;">
                            <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-300">Resultados Inmediatos</h3>
                    </div>
                </div>

                <div class="absolute bottom-0 w-full py-6">
                    <p class="text-center text-green-600 dark:text-green-400 text-sm animate__animated animate__fadeIn">
                        &copy; {{ date('Y') }} EcoMetric. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
