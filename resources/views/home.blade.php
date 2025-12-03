@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Dashboard General</h1>

    {{-- Tarjetas estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Clientes Registrados</h5>
                    <h2>{{ $totalClientes }}</h2>
                    <a href="{{ route('clientes.index') }}" class="btn btn-light">Ver Clientes</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ventas Totales</h5>
                    <h2>{{ $totalVentas }}</h2>
                    <a href="{{ route('ventas.index') }}" class="btn btn-light">Ver Ventas</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Facturas Emitidas</h5>
                    <h2>{{ $totalFacturas }}</h2>
                    <a href="{{ route('facturas.index') }}" class="btn btn-dark">Ver Facturas</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        <div class="col-md-6">
            <h4>Ventas por Mes</h4>
            <canvas id="ventasMesChart"></canvas>
        </div>

        <div class="col-md-6">
            <h4>Estado de Facturas</h4>
            <canvas id="facturasEstadoChart"></canvas>
        </div>
    </div>

</div>

{{-- Cargar Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // -----------------------------
    // Gráfico de Ventas por Mes
    // -----------------------------
    const ventasMesCtx = document.getElementById('ventasMesChart').getContext('2d');

    new Chart(ventasMesCtx, {
        type: 'bar',
        data: {
            labels: @json($ventasPorMes->keys()->map(fn($m) => "Mes $m")),
            datasets: [{
                label: 'Ventas registradas',
                data: @json($ventasPorMes->values()),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        },
    });

    // -----------------------------
    // Gráfico de Estado de Facturas
    // -----------------------------
    const facturasEstadoCtx = document.getElementById('facturasEstadoChart').getContext('2d');

    new Chart(facturasEstadoCtx, {
        type: 'pie',
        data: {
            labels: @json($facturasEstado->keys()),
            datasets: [{
                label: 'Cantidad',
                data: @json($facturasEstado->values()),
                backgroundColor: [
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                ]
            }]
        }
    });
</script>

@endsection
