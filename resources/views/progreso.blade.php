@extends('layouts.PlantillaBase')

@section('title', 'Progreso')

@section('content')
<div class="container py-5 bg-light rounded-3 shadow-sm">
    <h2 class="text-center mb-4 text-success">Progreso</h2>

    <!-- Lista de opciones con tarjetas -->
    <ul class="list-unstyled row row-cols-1 row-cols-md-2 g-4">
        <li class="col">
            <a href="{{ route('progreso.estadisticas') }}"
                class="d-flex align-items-center p-4 border rounded-3 shadow-sm bg-white text-dark text-decoration-none hover-shadow">
                <i class="fas fa-chart-bar fa-3x me-3"></i>
                <span>Ver Estadísticas</span>
            </a>
        </li>
        <li class="col">
            <a href="{{ route('progreso.logros') }}"
                class="d-flex align-items-center p-4 border rounded-3 shadow-sm bg-white text-dark text-decoration-none hover-shadow">
                <i class="fas fa-trophy fa-3x me-3"></i>
                <span>Logros</span>
            </a>
        </li>
        <li class="col">
            <a href="{{ route('progreso.activities') }}"
                class="d-flex align-items-center p-4 border rounded-3 shadow-sm bg-white text-dark text-decoration-none hover-shadow">
                <i class="fas fa-check-circle fa-3x me-3"></i>
                <span>Actividades Completadas</span>
            </a>
        </li>
        <li class="col">
            <a href="{{ route('ranking.index') }}"
                class="d-flex align-items-center p-4 border rounded-3 shadow-sm bg-white text-dark text-decoration-none hover-shadow">
                <i class="fas fa-list-ol fa-3x me-3"></i>
                <span>Ranking</span>
            </a>
        </li>
    </ul>

    <div class="text-center mt-4 p-4 rounded-3 shadow-sm bg-white">
        <img src="{{ asset('images/mascota-tortuga2.png') }}" alt="Mascota Tortuga"
            style="filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.5)); max-width: 200px; height: auto;">
    </div>


    <div class="text-center mt-4">
        <a href="{{ route('index') }}" class="btn btn-success btn-lg">Volver al Menú Principal</a>
    </div>
</div>
@endsection

@push('styles')
    <style>
        /* Efecto de hover para los enlaces */
        .hover-shadow:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .img-fluid {
            transition: transform 0.3s ease;
        }

        .img-fluid:hover {
            transform: scale(1.1);
        }
    </style>
@endpush