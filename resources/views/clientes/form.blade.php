<div class="mb-3">
    <label>Nombre:</label>
    <input type="text" name="nombre" 
           class="form-control @error('nombre') is-invalid @enderror"
           value="{{ old('nombre', $cliente->nombre ?? '') }}" required>

    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>RUT:</label>
    <input type="text" name="rut" 
           class="form-control @error('rut') is-invalid @enderror"
           value="{{ old('rut', $cliente->rut ?? '') }}" required>

    @error('rut')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Correo:</label>
    <input type="email" name="correo"
           class="form-control @error('correo') is-invalid @enderror"
           value="{{ old('correo', $cliente->correo ?? '') }}">

    @error('correo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Teléfono:</label>
    <input type="text" name="telefono"
           class="form-control @error('telefono') is-invalid @enderror"
           value="{{ old('telefono', $cliente->telefono ?? '') }}">

    @error('telefono')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Rubro:</label>
    <input type="text" name="rubro"
           class="form-control @error('rubro') is-invalid @enderror"
           value="{{ old('rubro', $cliente->rubro ?? '') }}">

    @error('rubro')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
