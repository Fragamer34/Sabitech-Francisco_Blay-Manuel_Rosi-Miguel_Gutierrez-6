<?php
 
namespace App\Http\Controllers;
 
use App\Models\Usuario;
use Illuminate\Http\Request;
 
class UsuarioController extends Controller
{
    /**
     * Listado de usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('created_at', 'desc')->paginate(10);
 
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
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }
 
    /**
     * Actualiza una charla.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponente'     => 'required|string|max:255',
            'fecha'       => 'required|date',
        ]);
 
        $usuario->update($datos);
 
        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
 
    /**
     * Elimina una charla.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
 
        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}