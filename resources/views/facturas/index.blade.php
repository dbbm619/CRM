@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Facturas</h1>

    <a href="{{ route('facturas.create') }}" class="btn btn-primary mb-3">Crear Factura</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>N° Factura</th>
                <th>Cliente</th>
                <th>Venta</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($facturas as $factura)
                <tr>
                    <td>{{ $factura->id }}</td>
                    <td>{{ $factura->numero_factura }}</td>
                    <td>{{ $factura->cliente->nombre ?? '—' }}</td>
                    <td>
                        Venta #{{ $factura->venta->id ?? '—' }}
                        @if(isset($factura->venta) && $factura->venta)
                            — ${{ number_format($factura->venta->monto, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>{{ $factura->fecha_emision }}</td>
                    <td>{{ ucfirst($factura->estado) }}</td>
                    <td>
                        <a href="{{ route('facturas.edit', $factura->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('facturas.destroy', $factura->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar factura?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
