@extends('layouts.PlantillaBase')

@section('title', 'Actividades Completadas')

@section('content')
<div class="section container py-5 bg-white">
    <h2 class="text-center mb-4 text-primary font-weight-bold">Actividades Completadas</h2>
    <p class="text-center mb-5 text-muted">Consulta tus actividades y los puntos obtenidos. ¡Sigue alcanzando nuevas metas!</p>

    @if ($activities->isEmpty())
        <div class="alert alert-info text-center shadow-lg rounded-lg" role="alert">
            <i class="fas fa-exclamation-circle"></i> No has completado ninguna actividad aún. ¡Empieza ahora!
        </div>
    @else
        <div class="table-responsive ">
            
            <table class="table table-hover table-light shadow-lg rounded-lg">
                <thead class="thead-dark">
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Puntos</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr class="hover-bg">
                            <td>
                                @php
                                    $iconClass = '';
                                    switch($activity->activity_type) {
                                        case 'quiz':
                                            $iconClass = 'fa-question-circle text-primary';
                                            break;
                                        case 'game':
                                            $iconClass = 'fa-gamepad text-success';
                                            break;
                                        case 'trivia':
                                            $iconClass = 'fa-brain text-info';
                                            break;
                                        case 'manualidades':
                                            $iconClass = 'fa-paint-brush text-warning';
                                            break;
                                        case 'historias':
                                            $iconClass = 'fa-book text-secondary';
                                            break;
                                        case 'experimentos':
                                            $iconClass = 'fa-flask text-danger';
                                            break;
                                        default:
                                            $iconClass = 'fa-check-circle text-muted';
                                    }
                                @endphp
                                <i class="fas {{ $iconClass }} mr-2"></i>
                                {{ ucfirst($activity->activity_type) }}
                            </td>
                            <td>{{ $activity->activity_name }}</td>
                            <td><span class="badge bg-success">+{{ $activity->points }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($activity->completed_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('progreso') }}" class="btn btn-lg btn-outline-primary shadow-lg">Volver a Progreso</a>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/actividades.css') }}">
@endsection
