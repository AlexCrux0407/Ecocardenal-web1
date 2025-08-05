@extends('layouts.PlantillaBase')

@section('title', 'Panel de Administración')

@section('content')
<div class="section container py-5 bg-light shadow-sm rounded" style="max-width: 900px; margin: 0 auto;">
    <h2 class="text-center mb-4">Panel de Administración</h2>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row justify-content-center">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Gestión de Logros</h5>
                </div>
                <div class="card-body">
                    <p>Desde aquí puedes resetear todos los logros desbloqueados por los usuarios.</p>
                    <p class="text-danger"><strong>Advertencia:</strong> Esta acción eliminará todos los registros de logros desbloqueados y no se puede deshacer.</p>
                    
                    <form action="{{ route('admin.reset-logros') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar TODOS los registros de logros desbloqueados? Esta acción no se puede deshacer.')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Resetear Todos los Logros
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botón de regreso -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-success px-4 py-2">
            <i class="fas fa-arrow-left"></i> Volver al Inicio
        </a>
    </div>
</div>
@endsection