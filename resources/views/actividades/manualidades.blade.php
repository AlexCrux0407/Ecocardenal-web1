@extends('layouts.PlantillaBase')

@section('title', 'Manualidades')

@section('content')

<div class="container my-5">
   <div class=" bg-white">
   <h2 class="text-center mb-4">Manualidades Reciclables</h2>
    <p class="text-center mb-5">Descubre cómo reutilizar materiales reciclables para crear objetos útiles y divertidos.</p>

   </div>
   
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <strong><i class="fas fa-check-circle"></i> ¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong><i class="fas fa-exclamation-circle"></i> Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- Alcancía con Botellas de Plástico -->
        <div class="col">
            <div class="card d-flex flex-column h-100 shadow-sm">
                <img src="https://img.youtube.com/vi/75B8pGCk4Y4/0.jpg" class="card-img-top" alt="Alcancía con Botellas de Plástico">
                <div class="card-body">
                    <h5 class="card-title">1. Alcancía con Botellas de Plástico</h5>
                    <p class="card-text">Transforma una botella de plástico en una práctica hucha.</p>
                    <h6>Materiales:</h6>
                    <ul>
                        <li>1 botella de plástico</li>
                        <li>Pintura acrílica (opcional)</li>
                        <li>Tijeras</li>
                        <li>Cinta adhesiva decorativa</li>
                    </ul>
                    <h6>Pasos:</h6>
                    <ol>
                        <li>Recorta la parte superior de la botella para hacer una abertura donde caerán las monedas.</li>
                        <li>Decora la botella con pintura y cinta adhesiva.</li>
                        <li>Asegúrate de que la tapa esté bien cerrada para mantener las monedas seguras.</li>
                    </ol>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="https://www.youtube.com/watch?v=75B8pGCk4Y4" class="btn btn-success" target="_blank">Ver video</a>
                        <form action="{{ route('manualidades.completar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="manualidad_id" value="1">
                            <input type="hidden" name="nombre_manualidad" value="Alcancía con Botellas de Plástico">
                            <button type="submit" class="btn btn-primary">¡Completé esta manualidad!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Macetas con Latas de Aluminio -->
        <div class="col">
            <div class="card d-flex flex-column h-100 shadow-sm">
                <img src="https://img.youtube.com/vi/QRcBMVNE1z4/0.jpg" class="card-img-top" alt="Macetas con Latas de Aluminio">
                <div class="card-body">
                    <h5 class="card-title">2. Macetas con Latas de Aluminio</h5>
                    <p class="card-text">Dale una nueva vida a las latas convirtiéndolas en macetas decorativas.</p>
                    <h6>Materiales:</h6>
                    <ul>
                        <li>1 lata de aluminio</li>
                        <li>Pintura en aerosol</li>
                        <li>Tierra y una planta pequeña</li>
                        <li>Punzón o clavo para hacer agujeros</li>
                    </ul>
                    <h6>Pasos:</h6>
                    <ol>
                        <li>Haz pequeños agujeros en la base de la lata para el drenaje del agua.</li>
                        <li>Pinta la lata con el diseño de tu preferencia usando pintura en aerosol.</li>
                        <li>Llena la lata con tierra y planta tu planta favorita.</li>
                    </ol>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="https://www.youtube.com/watch?v=QRcBMVNE1z4" class="btn btn-success" target="_blank">Ver video</a>
                        <form action="{{ route('manualidades.completar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="manualidad_id" value="2">
                            <input type="hidden" name="nombre_manualidad" value="Macetas con Latas de Aluminio">
                            <button type="submit" class="btn btn-primary">¡Completé esta manualidad!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lámpara con Cartón y Papel -->
        <div class="col">
            <div class="card d-flex flex-column h-100 shadow-sm">
                <img src="https://img.youtube.com/vi/GwujtQajDy4/0.jpg" class="card-img-top" alt="Lámpara con Cartón y Papel">
                <div class="card-body">
                    <h5 class="card-title">3. Lámpara con Cartón y Papel</h5>
                    <p class="card-text">Crea una lámpara original y ecológica con cartón y papel reciclado.</p>
                    <h6>Materiales:</h6>
                    <ul>
                        <li>Cartón reciclado</li>
                        <li>Papel decorativo reciclado</li>
                        <li>Tijeras o cúter</li>
                        <li>Pegamento</li>
                        <li>Una luz LED</li>
                    </ul>
                    <h6>Pasos:</h6>
                    <ol>
                        <li>Recorta el cartón en forma de base y estructura de la lámpara.</li>
                        <li>Pega el papel decorativo sobre el cartón para darle color.</li>
                        <li>Ensambla la estructura y coloca la luz LED en el interior.</li>
                    </ol>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="https://www.youtube.com/watch?v=GwujtQajDy4&list=PL8Z2847EZSQpoWoa7kWCnqUi_MAgP8OHw" class="btn btn-success" target="_blank">Ver video</a>
                        <form action="{{ route('manualidades.completar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="manualidad_id" value="3">
                            <input type="hidden" name="nombre_manualidad" value="Lámpara con Cartón y Papel">
                            <button type="submit" class="btn btn-primary">¡Completé esta manualidad!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('actividades') }}" class="btn btn-success">Volver a Actividades</a>
    </div>
    

</div>

<link rel="stylesheet" href="{{ asset('css/manualidades.css') }}">

@endsection
