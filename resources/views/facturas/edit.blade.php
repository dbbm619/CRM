@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Factura</h1>

        {{-- Recuadro de errores --}}
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

        <form action="{{ route('facturas.update', $factura->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Cliente</label>
                <select name="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror">
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            {{ old('cliente_id', $factura->cliente_id) == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Venta</label>
                <select name="venta_id" class="form-control @error('venta_id') is-invalid @enderror">
                    @foreach ($ventas as $venta)
                        <option value="{{ $venta->id }}"
                            {{ old('venta_id', $factura->venta_id) == $venta->id ? 'selected' : '' }}>
                            Venta #{{ $venta->id }} — Cliente: {{ $venta->cliente->nombre ?? '—' }} —
                            ${{ number_format($venta->monto, 0, ',', '.') }} — {{ $venta->fecha }}
                        </option>
                    @endforeach
                </select>
                @error('venta_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Número de Factura <small class="text-muted">(ej: F001, F-001, FAC-0001)</small></label>
                <input type="text" name="numero_factura"
                    class="form-control @error('numero_factura') is-invalid @enderror"
                    value="{{ old('numero_factura', $factura->numero_factura) }}">
                @error('numero_factura')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Fecha de Emisión</label>
                <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror"
                    value="{{ old('fecha_emision', $factura->fecha_emision) }}">
                @error('fecha_emision')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                    <option value="emitida" {{ old('estado', $factura->estado) == 'emitida' ? 'selected' : '' }}>Emitida
                    </option>
                    <option value="pagada" {{ old('estado', $factura->estado) == 'pagada' ? 'selected' : '' }}>Pagada
                    </option>
                    <option value="anulada" {{ old('estado', $factura->estado) == 'anulada' ? 'selected' : '' }}>Anulada
                    </option>
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('facturas.index') }}" class="btn btn-secondary me-2">Volver</a>
            <button class="btn btn-success">Actualizar Factura</button>
        </form>
    </div>
@endsection
