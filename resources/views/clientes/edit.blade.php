@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Cliente</h1>

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

        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('clientes.form')
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary me-2">Volver</a>
            <button class="btn btn-success">Actualizar Cliente</button>
        </form>
    </div>
@endsection
