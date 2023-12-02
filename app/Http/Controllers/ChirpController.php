<?php
// Form Request Validation, which makes it easy to re-use validation
// rules and to keep your controllers light.
namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //return view('chirps.index');

        //Utilizamos el método with de Eloquent 
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

     /*Usamos la función de validación de Laravel para garantizar
      que el usuario proporcione un mensaje y que no exceda el límite
       de 255 caracteres de las columnas de la base de datos */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message'=> 'required|string|max:255',
        ]);

        $request->user()->chirps()->create($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        // Laravel cargará automáticamente el modelo Chirp desde la base de datos 
        // utilizando el enlace del modelo de ruta para pasarlo directamente
        // a la vista.
        $this->authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        //Lógica para actualizar un Chirp
        $this->authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        //
        $this->authorize('delete', $chirp);
        $chirp->delete();
        return redirect(route('chirps.index'));
    }
}
