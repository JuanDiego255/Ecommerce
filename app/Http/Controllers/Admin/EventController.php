<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        //Gate::authorize('viewAny', Event::class);
        $events = Event::latest()->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        Gate::authorize('create', Event::class);
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen_premios')) {
            $data['imagen_premios'] = $request->file('imagen_premios')
                ->store('premios', 'public'); // pública si quieres mostrarla
        }

        // Si se marca activo, desactiva otros (solo un formulario activo)
        if (!empty($data['activo']) && $data['activo']) {
            Event::where('activo', true)->update(['activo' => false]);
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Evento creado.');
    }

    public function edit(Event $event)
    {
        //Gate::authorize('update', $event);
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen_premios')) {
            if ($event->imagen_premios) Storage::disk('public')->delete($event->imagen_premios);
            $data['imagen_premios'] = $request->file('imagen_premios')
                ->store('premios', 'public');
        }

        if (!empty($data['activo']) && $data['activo']) {
            Event::where('id', '!=', $event->id)->where('activo', true)->update(['activo' => false]);
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Evento actualizado.');
    }

    public function destroy($id)
    {
        //Gate::authorize('delete', $event);
        DB::beginTransaction();
        try {
            $event = Event::findOrfail($id);
            if (
                Storage::delete('public/' . $event->image)
            ) {
                Event::destroy($id);
            }
            Event::destroy($id);
            DB::commit();
            return back()->with('success', 'Evento eliminado.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('/categories')->with(['status' => 'Ocurrió un error al eliminar la categoría!', 'icon' => 'error']);
        }
    }
}
