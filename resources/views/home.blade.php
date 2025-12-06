@extends('layouts.app')

@section('content')
    <div class="container">

        <h1 class="mb-4">Dashboard General</h1>

        {{-- ======================= --}}
        {{--  FORMULARIO DE FILTRO   --}}
        {{-- ======================= --}}
        <form method="GET" action="{{ route('home') }}" class="mb-4 p-3 border rounded bg-light">

            <div class="row">

                <div class="col-md-3">
                    <label>Desde</label>
                    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                </div>

                <div class="col-md-3">
                    <label>Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                </div>

                <div class="col-md-3">
                    <label>Cliente</label>
                    <select name="cliente_id" class="form-control">
                        <option value="">Todos</option>
                        @foreach ($listaClientes as $id => $nombre)
                            <option value="{{ $id }}" {{ request('cliente_id') == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tipo Cliente</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        <option value="recurrente" {{ request('tipo') == 'recurrente' ? 'selected' : '' }}>Recurrente
                        </option>
                        <option value="oneshot" {{ request('tipo') == 'oneshot' ? 'selected' : '' }}>One-Shot</option>
                    </select>
                </div>

            </div>

            <button class="btn btn-primary mt-3">Filtrar</button>
            <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Limpiar</a>

        </form>

        {{-- ======================= --}}
        {{--  TARJETAS ESTADÍSTICAS  --}}
        {{-- ======================= --}}
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

            <div class="col-md-6">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Cuenta por Cobrar (Pendientes)</h5>
                        <h2>${{ number_format($cuentaPorCobrar, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pérdidas (Canceladas)</h5>
                        <h2>${{ number_format($perdidas, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>

        </div>

        {{-- ======================= --}}
        {{--        GRÁFICOS         --}}
        {{-- ======================= --}}
        <div class="row">

            <div class="col-md-6">
                <h4>Ventas por Mes</h4>
                <canvas id="ventasMesChart"></canvas>
            </div>

            <div class="col-md-6">
                <h4>Estado de Facturas</h4>
                <canvas id="facturasEstadoChart"></canvas>
            </div>

            <div class="col-md-6 mt-5">
                <h4>Estado de Ventas</h4>
                <canvas id="ventasEstadoChart"></canvas>
            </div>

            <div class="col-md-6 mt-5">
                <h4>Ingresos Totales por Mes</h4>
                <canvas id="montosMesChart"></canvas>
            </div>

            <div class="col-md-6 mt-5">
                <h4>Flujo Real de Caja Mensual</h4>
                <canvas id="flujoCajaChart"></canvas>
            </div>

            <div class="col-md-12 mt-5">
                <h4>Clientes con más Ventas</h4>
                <canvas id="ventasClienteChart"></canvas>
            </div>

        </div>

    </div>

    {{-- Cargar Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Gráfico Ventas por Mes
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

        // Gráfico Estado Facturas
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

        // Gráfico Estado Ventas
        const ventasEstadoCtx = document.getElementById('ventasEstadoChart').getContext('2d');
        new Chart(ventasEstadoCtx, {
            type: 'pie',
            data: {
                labels: @json($ventasEstado->keys()),
                datasets: [{
                    data: @json($ventasEstado->values()),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)', // pagada
                        'rgba(255, 205, 86, 0.7)', // pendiente
                        'rgba(255, 99, 132, 0.7)' // cancelada
                    ]
                }]
            }
        });

        // Gráfico Montos por Mes
        const montosMesCtx = document.getElementById('montosMesChart').getContext('2d');
        new Chart(montosMesCtx, {
            type: 'line',
            data: {
                labels: @json($montosPorMes->keys()->map(fn($m) => "Mes $m")),
                datasets: [{
                    label: 'Ingresos Mensuales ($)',
                    data: @json($montosPorMes->values()),
                    borderWidth: 2,
                    fill: true,
                }]
            }
        });

        // Gráfico Flujo Real De Caja
        const flujoCajaCtx = document.getElementById('flujoCajaChart').getContext('2d');
        new Chart(flujoCajaCtx, {
            type: 'line',
            data: {
                labels: @json($flujoCaja->keys()->map(fn($m) => "Mes $m")),
                datasets: [{
                    label: 'Ingreso Real ($)',
                    data: @json($flujoCaja->values()),
                    borderWidth: 2,
                    fill: true,
                }]
            }
        });

        // Gráfico Ventas por Cliente
        const ventasClienteCtx = document.getElementById('ventasClienteChart').getContext('2d');
        new Chart(ventasClienteCtx, {
            type: 'bar',
            data: {
                labels: @json($nombresClientes->values()),
                datasets: [{
                    label: 'Cantidad de Ventas',
                    data: @json($ventasPorCliente->values()),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                }]
            },
        });
    </script>
@endsection
