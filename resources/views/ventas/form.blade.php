<div class="mb-3">
    <label>Cliente</label>
    <select name="cliente_id" 
            class="form-control @error('cliente_id') is-invalid @enderror">
        <option value="">Seleccione un cliente</option>

        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}"
                {{ old('cliente_id', $venta->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                {{ $cliente->nombre }}
            </option>
        @endforeach
    </select>

    @error('cliente_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Monto</label>
    <input type="number" name="monto" 
           class="form-control @error('monto') is-invalid @enderror"
           value="{{ old('monto', $venta->monto ?? '') }}">

    @error('monto')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Fecha</label>
    <input type="date" name="fecha" 
           class="form-control @error('fecha') is-invalid @enderror"
           value="{{ old('fecha', $venta->fecha ?? '') }}">

    @error('fecha')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Estado</label>
    <select name="estado" 
            class="form-control @error('estado') is-invalid @enderror">
        <option value="pendiente" {{ old('estado', $venta->estado ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="pagada" {{ old('estado', $venta->estado ?? '') == 'pagada' ? 'selected' : '' }}>Pagada</option>
        <option value="cancelada" {{ old('estado', $venta->estado ?? '') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
    </select>

    @error('estado')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
