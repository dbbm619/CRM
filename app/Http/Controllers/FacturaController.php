<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::with(['cliente', 'venta'])->get();
        return view('facturas.index', compact('facturas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        // traer cliente en ventas para mostrar info clara en el select
        $ventas = Venta::with('cliente')->get();
        return view('facturas.create', compact('clientes', 'ventas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'venta_id' => 'required|exists:ventas,id',
            'numero_factura' => [
                'required',
                'regex:/^(F|F-|FAC-)\d{3,5}$/',
                'unique:facturas,numero_factura'
            ],
            'fecha_emision' => [
                'required',
                'regex:/^\d{4}\-\d{2}\-\d{2}$/',
                // closure para validar checkdate y rango
                function ($attribute, $value, $fail) {
                    $parts = explode('-', $value);
                    if (count($parts) !== 3) {
                        $fail('La fecha debe tener el formato DD-MM-AAAA.');
                        return;
                    }
                    [$year, $month, $day] = $parts;
                    if (!checkdate((int)$month, (int)$day, (int)$year)) {
                        $fail('Debe ingresar una fecha válida.');
                        return;
                    }
                    if ((int)$year < 1900 || (int)$year > 2100) {
                        $fail('El año debe estar entre 1900 y 2100.');
                        return;
                    }
                    if ($value > date('Y-m-d')) {
                        $fail('La fecha no puede ser futura.');
                        return;
                    }
                }
            ],
            'estado' => 'required|in:emitida,pagada,anulada',
        ],[
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',

            'venta_id.required' => 'Debe seleccionar una venta.',
            'venta_id.exists' => 'La venta seleccionada no existe.',

            'numero_factura.required' => 'Debe ingresar un número de factura.',
            'numero_factura.regex' => 'El número de factura debe tener un formato válido (ej: F001, F-001, FAC-0001).',
            'numero_factura.unique' => 'Este número de factura ya está registrado.',

            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
            'fecha_emision.regex' => 'La fecha debe tener el formato DD-MM-AAAA.',
            'fecha_emision.before_or_equal' => 'La fecha no puede ser futura.',

            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        // Validar que la venta pertenezca al cliente seleccionado
        $venta = Venta::find($request->venta_id);
        if (!$venta || $venta->cliente_id != (int)$request->cliente_id) {
            return back()
                ->withInput()
                ->withErrors(['venta_id' => 'La venta seleccionada no pertenece al cliente elegido.']);
        }

        Factura::create($request->all());

        return redirect()->route('facturas.index')
            ->with('success', 'Factura creada correctamente.');
    }

    public function edit(Factura $factura)
    {
        $clientes = Cliente::all();
        $ventas = Venta::with('cliente')->get();
        return view('facturas.edit', compact('factura', 'clientes', 'ventas'));
    }

    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'venta_id' => 'required|exists:ventas,id',
            'numero_factura' => [
                'required',
                'regex:/^(F|F-|FAC-)\d{3,5}$/',
                'unique:facturas,numero_factura,' . $factura->id
            ],
            'fecha_emision' => [
                'required',
                'regex:/^\d{4}\-\d{2}\-\d{2}$/',
                function ($attribute, $value, $fail) {
                    $parts = explode('-', $value);
                    if (count($parts) !== 3) {
                        $fail('La fecha debe tener el formato DD-MM-AAAA.');
                        return;
                    }
                    [$year, $month, $day] = $parts;
                    if (!checkdate((int)$month, (int)$day, (int)$year)) {
                        $fail('Debe ingresar una fecha válida.');
                        return;
                    }
                    if ((int)$year < 1900 || (int)$year > 2100) {
                        $fail('El año debe estar entre 1900 y 2100.');
                        return;
                    }
                    if ($value > date('Y-m-d')) {
                        $fail('La fecha no puede ser futura.');
                        return;
                    }
                }
            ],
            'estado' => 'required|in:emitida,pagada,anulada',
        ],[
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',

            'venta_id.required' => 'Debe seleccionar una venta.',
            'venta_id.exists' => 'La venta seleccionada no existe.',

            'numero_factura.required' => 'Debe ingresar un número de factura.',
            'numero_factura.regex' => 'El número de factura debe tener un formato válido (ej: F001, F-001, FAC-0001).',
            'numero_factura.unique' => 'Este número de factura ya está registrado.',

            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
            'fecha_emision.regex' => 'La fecha debe tener el formato DD-MM-AAAA.',
            'fecha_emision.before_or_equal' => 'La fecha no puede ser futura.',

            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        // Validar que la venta pertenezca al cliente seleccionado
        $venta = Venta::find($request->venta_id);
        if (!$venta || $venta->cliente_id != (int)$request->cliente_id) {
            return back()
                ->withInput()
                ->withErrors(['venta_id' => 'La venta seleccionada no pertenece al cliente elegido.']);
        }

        $factura->update($request->all());

        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura eliminada correctamente.');
    }
}
