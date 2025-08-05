@extends('layouts.PlantillaBase')

@section('title', 'Juega un Juego')

@section('content')
<link rel="stylesheet" href="{{ asset('css/juego.css') }}">

<div class="section center">
    <h2 class="game-title">Juego de Memoria - Cuida el Medio Ambiente</h2>
    <p class="game-description">Encuentra las parejas de imágenes relacionadas con el medio ambiente.</p>

    <div id="game-board" class="game-board">
        <!-- Las cartas del juego se generarán aquí -->
    </div>

    <div class="game-controls d-flex justify-content-center">
        <button id="restart-button" class="btn btn-primary btn-lg mt-4 mx-2">Reiniciar Juego</button>
        <form action="{{ route('juego.finish') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" id="complete-button" class="btn btn-success btn-lg mt-4 mx-2">Completar Juego</button>
        </form>
        <a href="{{ route('actividades') }}" class="btn btn-outline-secondary btn-lg mt-4 mx-2">Volver a Actividades</a>
    </div>
</div>

<!-- Incluir el script del juego -->
<script src="{{ asset('js/juego.js') }}"></script>
<!-- Incluir el estilo del juego -->
<link rel="stylesheet" href="{{ asset('css/juego.css') }}">
@endsection
