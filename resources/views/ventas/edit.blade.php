@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Venta</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Hay errores en el formulario:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ventas.update', $venta->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            @include('ventas.form')

            <a href="{{ route('ventas.index') }}" class="btn btn-secondary me-2">Volver</a>
            <button class="btn btn-success">Actualizar Venta</button>
        </form>
    </div>
@endsection
