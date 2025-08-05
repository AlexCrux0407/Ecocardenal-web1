@extends('layouts.PlantillaBase')

@section('title', 'Ver Estadísticas')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estadisticas.css') }}">
@endpush

@section('content')
<div class="section container py-5 bg-white shadow-sm rounded" style="max-width: 900px; margin: 0 auto;">
    <h2 class="text-center mb-4">Estadísticas de tu Progreso</h2>
    <p class="text-center mb-5">Consulta tu avance y metas alcanzadas.</p>

    <!-- Progress Bar -->
    <div class="progress-container mb-4">
        <div class="d-flex justify-content-between mb-2">
            <span>Tu progreso</span>
            <span>{{ $porcentajeCompletado }}%</span>
        </div>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar progress-bar-striped bg-success" 
                 role="progressbar" 
                 style="width: {{ $porcentajeCompletado }}%;" 
                 aria-valuenow="{{ $porcentajeCompletado }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $porcentajeCompletado }}%
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row text-center mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-trophy fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">Logros obtenidos</h5>
                    <p class="display-4">{{ $logrosObtenidos }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-star fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Puntos totales</h5>
                    <p class="display-4">{{ $puntosTotales }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Actividades completadas</h5>
                    <p class="display-4">{{ $actividadesCompletadas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="row mb-4">
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Logros</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Logros desbloqueados</span>
                        <a href="{{ route('progreso.logros') }}" class="btn btn-sm btn-outline-success">Ver todos</a>
                    </div>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $logrosDesbloqueados / max($totalLogros, 1) * 100 }}%;" 
                             aria-valuenow="{{ $logrosDesbloqueados }}" aria-valuemin="0" aria-valuemax="{{ $totalLogros }}">
                            {{ number_format($logrosDesbloqueados / max($totalLogros, 1) * 100, 0) }}%
                        </div>
                    </div>
                    <p class="mb-0">{{ $logrosDesbloqueados }} de {{ $totalLogros }} logros desbloqueados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-between">
        <a href="{{ route('progreso') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Progreso
        </a>
        <a href="{{ route('ranking.index') }}" class="btn btn-primary">
            Ver Ranking <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<!-- Optional: Add animation to progress bar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(function(bar) {
        bar.style.transition = 'width 1.5s ease-in-out';
    });
});
</script>

@endsection