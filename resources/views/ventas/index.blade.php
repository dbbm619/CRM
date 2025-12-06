@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ventas</h1>

    <a href="{{ route('ventas.create') }}" class="btn btn-primary mb-3">Crear Venta</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->cliente->nombre }}</td>
                    <td>${{ number_format($venta->monto, 0, ',', '.') }}</td>
                    <td>{{ $venta->fecha }}</td>
                    <td>{{ ucfirst($venta->estado) }}</td>
                    <td>
                        <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" 
                              style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Seguro que deseas eliminar esta venta?')">
                                Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
