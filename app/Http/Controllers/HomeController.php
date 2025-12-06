<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use App\Models\Factura;

class HomeController extends Controller
{
    public function index()
    {
        // 🟦 Lista de clientes para el filtro
        $listaClientes = Cliente::pluck('nombre', 'id');

        // Obtener filtros
        $desde   = request('desde');
        $hasta   = request('hasta');
        $cliente = request('cliente_id');
        $tipo    = request('tipo');

        $ventasQuery = Venta::query();
        $facturasQuery = Factura::query();

        // 🟢 FILTRO POR FECHAS
        if ($desde) {
            $ventasQuery->whereDate('fecha', '>=', $desde);
            $facturasQuery->whereDate('fecha_emision', '>=', $desde);
        }
        if ($hasta) {
            $ventasQuery->whereDate('fecha', '<=', $hasta);
            $facturasQuery->whereDate('fecha_emision', '<=', $hasta);
        }

        // 🟢 FILTRO POR CLIENTE
        if ($cliente) {
            $ventasQuery->where('cliente_id', $cliente);
        }

        // 🟢 FILTRO POR TIPO DE CLIENTE (recurrente / one-shot)
        if ($tipo == 'recurrente') {
            $clientesRecurrentes = Venta::selectRaw('cliente_id, COUNT(*) as total')
                ->groupBy('cliente_id')
                ->having('total', '>', 1)
                ->pluck('cliente_id');

            $ventasQuery->whereIn('cliente_id', $clientesRecurrentes);
        }

        if ($tipo == 'oneshot') {
            $clientesOneShot = Venta::selectRaw('cliente_id, COUNT(*) as total')
                ->groupBy('cliente_id')
                ->having('total', '=', 1)
                ->pluck('cliente_id');

            $ventasQuery->whereIn('cliente_id', $clientesOneShot);
        }

        // Ejecutar las consultas filtradas
        $ventasFiltradas = $ventasQuery->get();
        $facturasFiltradas = $facturasQuery->get();

        // Contadores filtrados
        $totalClientes = $listaClientes->count();
        $totalVentas   = $ventasFiltradas->count();
        $totalFacturas = $facturasFiltradas->count();

        // ================================
        // NUEVAS TARJETAS
        // ================================

        // Total por cobrar (ventas pendientes)
        $cuentaPorCobrar = $ventasFiltradas
            ->where('estado', 'pendiente')
            ->sum('monto');

        // Total pérdidas (ventas canceladas)
        $perdidas = $ventasFiltradas
            ->where('estado', 'cancelada')
            ->sum('monto');

        // -----------------------
        // 🟥 GRÁFICOS
        // -----------------------

        // 🟦 Ventas por mes
        $ventasPorMes = $ventasFiltradas
            ->groupBy(fn($v) => date('m', strtotime($v->fecha)))
            ->map->count();

        // 🟦 Montos por mes
        // Montos por mes (solo ventas pagadas)
        $montosPorMes = $ventasFiltradas
            ->where('estado', 'pagada')
            ->groupBy(fn($v) => date('m', strtotime($v->fecha)))
            ->map->sum('monto');

        // =======================================
        // NUEVO GRÁFICO: ESTADO DE VENTAS
        // =======================================
        $ventasEstado = $ventasFiltradas
            ->groupBy('estado')
            ->map->count();

        // =======================================
        // NUEVO GRÁFICO: FLUJO REAL DE CAJA
        // (solo montos pagados por mes)
        // =======================================
        $flujoCaja = $ventasFiltradas
            ->where('estado', 'pagada')
            ->groupBy(fn($v) => date('m', strtotime($v->fecha)))
            ->map->sum('monto');


        // 🟦 Estado facturas
        $facturasEstado = $facturasFiltradas
            ->groupBy('estado')
            ->map->count();

        // 🟦 Ventas por cliente
        $ventasPorCliente = $ventasFiltradas
            ->groupBy('cliente_id')
            ->map->count();

        $nombresClientes = Cliente::whereIn('id', $ventasPorCliente->keys())
            ->pluck('nombre', 'id');

        return view('home', compact(
            'listaClientes',
            'totalClientes',
            'totalVentas',
            'totalFacturas',
            'ventasPorMes',
            'montosPorMes',
            'facturasEstado',
            'ventasPorCliente',
            'nombresClientes',
            'cuentaPorCobrar',
            'perdidas',
            'ventasEstado',
            'flujoCaja'
        ));
    }
}
