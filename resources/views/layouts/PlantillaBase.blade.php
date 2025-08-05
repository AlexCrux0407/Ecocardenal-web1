<!DOCTYPE html>
<meta name="csrf-token" content="{{ csrf_token() }}">

<html lang="es">

<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Página de Hábitos')</title>
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    @stack('styles')
    <style>
        
        /* Estilos para el navbar */
        nav {
            z-index: 1050;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: #28a745;
        }

        /* Estilos de la barra lateral */
        .sidebar {
            width: 300px;
            height: 100vh;
            position: fixed;
            top: 0;
            right: -300px;
            /* Oculto fuera de la pantalla */
            background-color: #f8f9fa;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease-in-out;
            z-index: 1040;
        }

        .sidebar.active {
            right: 0;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1039;
        }

        .overlay.active {
            display: block;
        }

        .list-group-item {
            cursor: pointer;
            transition: all 0.3s;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        /* Estilos para los iconos */
        .fa {
            width: 30px;
            text-align: center;
        }

        /* Estilo activo en la barra lateral */
        .list-group-item.active {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('trivia.index') }}">TRIVIA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('index') }}">INICIO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('actividades') }}">ACTIVIDADES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('progreso') }}">PROGRESO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="configuracion-link">CONFIGURACIÓN</a>
                    </li>
                </ul>
            </div>
        </div>

    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <h5 class="text-primary">Configuración</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center">
                    <a href="{{ route('informacionUsuario') }}" class="list-group-item d-flex align-items-center">
                        <i class="fa fa-user-circle me-2" aria-label="Ajuste de Perfil"></i> Perfil
                    </a>

                </li>
                
               <li class="list-group-item d-flex align-items-center">
    <a href="{{ route('logout') }}" class="list-group-item d-flex align-items-center">
        <i class="fas fa-sign-out-alt me-2" aria-label="Cerrar sesión"></i> Salir
    </a>
</li>
<li class="list-group-item d-flex align-items-center">
                    <a href="{{ route('atencion.cliente') }}" class="list-group-item d-flex align-items-center">
                        <i class="fa fa-headset me-2"></i> Atención al Cliente
                    </a>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <a href="{{ route('faq.index') }}" class="d-flex align-items-center text-success">
                        <i class="fa fa-question-circle me-2"></i> Preguntas Frecuentes
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        @yield('content')
    </div>

    <script>
        const configuracionLink = document.getElementById('configuracion-link');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        // Abrir la barra lateral
        configuracionLink.addEventListener('click', (e) => {
            e.preventDefault(); // Evitar navegación
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        // Cerrar la barra lateral
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Resaltar la opción activa de la barra lateral
        const listItems = document.querySelectorAll('.list-group-item');
        listItems.forEach(item => {
            item.addEventListener('click', () => {
                listItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            });
        });
    </script>

</body>

</html>

@include('chat_popup')

