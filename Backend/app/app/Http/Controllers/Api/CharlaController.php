<?php
 
namespace App\Http\Controllers;
 
use App\Models\Charla;
use Illuminate\Http\Request;
 
class CharlaController extends Controller
{
    /**
     * Listado de charlas.
     */
    public function index()
    {
        $charlas = Charla::orderBy('fecha', 'desc')->paginate(10);
 
        return view('charlas.index', compact('charlas'));
    }
 

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponente'     => 'required|string|max:255',
            'fecha'       => 'required|date',
        ]);
 
        Charla::create($datos);
 
        return redirect()
            ->route('charlas.index')
            ->with('success', 'Charla creada correctamente.');
    }
 
    /**
     * Muestra una charla.
     */
    public function show(Charla $charla)
    {
        return view('charlas.show', compact('charla'));
    }
 
    /**
     * Formulario para editar una charla.
     */
    public function edit(Charla $charla)
    {
        return view('charlas.edit', compact('charla'));
    }
 
    /**
     * Actualiza una charla.
     */
    public function update(Request $request, Charla $charla)
    {
        $datos = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponente'     => 'required|string|max:255',
            'fecha'       => 'required|date',
        ]);
 
        $charla->update($datos);
 
        return redirect()
            ->route('charlas.index')
            ->with('success', 'Charla actualizada correctamente.');
    }
 
    /**
     * Elimina una charla.
     */
    public function destroy(Charla $charla)
    {
        $charla->delete();
 
        return redirect()
            ->route('charlas.index')
            ->with('success', 'Charla eliminada correctamente.');
    }
}