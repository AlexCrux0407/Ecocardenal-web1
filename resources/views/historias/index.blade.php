@extends('layouts.PlantillaBase')

@section('title', 'Historias')

@section('content')
<div class="section container py-5 bg-white shadow-sm rounded" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">Historias de la Comunidad</h2>
    <p class="text-center mb-5">Lee y comparte historias con nuestra comunidad.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('historias.store') }}" method="POST" class="form-story mb-5">
        @csrf
        <div class="form-group mb-3">
            <label for="nombre">Nombre (opcional):</label>
            <input type="text" name="nombre" id="nombre" placeholder="Anónimo" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="contenido">Escribe tu historia:</label>
            <textarea name="contenido" id="contenido" rows="5" placeholder="Comparte tu historia..." required class="form-control"></textarea>
        </div>
        <button type="submit" >Publicar Historia</button>
    </form>

    <div class="card-container">
        @foreach($historias as $historia)
        <div class="card mb-4 p-4 shadow-sm rounded">
            <p class="author font-weight-bold ">{{ $historia->nombre ?? 'Anónimo' }}</p>
            <p>{{ $historia->contenido }}</p>
            <p class="date text-muted">Publicado el {{ $historia->created_at->format('d/m/Y') }}</p>
            <form action="{{ route('historias.destroy', $historia->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm mt-2">Eliminar</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/historias.css') }}">

@endsection
