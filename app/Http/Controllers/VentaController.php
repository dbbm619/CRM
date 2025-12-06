<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente')->get(); // incluir datos del cliente
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all(); // para llenar el select
        return view('ventas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:1',
            'fecha' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    // Validar que sea una fecha realmente válida
                    [$year, $month, $day] = explode('-', $value);

                    if (!checkdate((int)$month, (int)$day, (int)$year)) {
                        $fail('Debe ingresar una fecha válida.');
                    }

                    // Limitar rango de años a algo realista
                    if ($year < 1900 || $year > 2100) {
                        $fail('El año debe estar entre 1900 y 2100.');
                    }
                }
            ],

            'estado' => 'required|string'
        ], [
            // Cliente
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists'   => 'El cliente seleccionado no existe.',

            // Monto
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric'  => 'El monto debe ser un número.',
            'monto.min'      => 'El monto debe ser mayor o igual a 1.',

            // Fecha
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date_format' => 'La fecha debe estar en formato válido (DD-MM-AAAA).',
            'fecha.before_or_equal' => 'La fecha no puede ser una fecha futura o inválida.',

            // Estado
            'estado.required' => 'Debe seleccionar un estado válido.'
        ]);


        Venta::create($request->all());

        return redirect()->route('ventas.index')->with('success', 'Venta creada correctamente.');
    }

    public function edit(Venta $venta)
    {
        $clientes = Cliente::all();
        return view('ventas.edit', compact('venta', 'clientes'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:1',
            'fecha' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    [$year, $month, $day] = explode('-', $value);

                    if (!checkdate((int)$month, (int)$day, (int)$year)) {
                        $fail('Debe ingresar una fecha válida.');
                    }

                    if ($year < 1900 || $year > 2100) {
                        $fail('El año debe estar entre 1900 y 2100.');
                    }
                }
            ],

            'estado' => 'required|string'
        ], [
            // Cliente
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists'   => 'El cliente seleccionado no existe.',

            // Monto
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric'  => 'El monto debe ser un número.',
            'monto.min'      => 'El monto debe ser mayor o igual a 1.',

            // Fecha
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date_format' => 'La fecha debe estar en formato válido (DD-MM-AAAA).',
            'fecha.before_or_equal' => 'La fecha no puede ser una fecha futura o inválida.',

            // Estado
            'estado.required' => 'Debe seleccionar un estado válido.'
        ]);

        $venta->update($request->all());

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }
}
