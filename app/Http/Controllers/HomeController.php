<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use App\Models\Factura;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'totalClientes' => Cliente::count(),
            'totalVentas'   => Venta::count(),
            'totalFacturas' => Factura::count(),

            'ventasPorMes'  => Venta::selectRaw('MONTH(fecha) as mes, COUNT(*) as total')
                                 ->groupBy('mes')
                                 ->orderBy('mes')
                                 ->pluck('total', 'mes'),

            'facturasEstado' => Factura::selectRaw('estado, COUNT(*) as total')
                                   ->groupBy('estado')
                                   ->pluck('total', 'estado'),
        ]);
    }
}
