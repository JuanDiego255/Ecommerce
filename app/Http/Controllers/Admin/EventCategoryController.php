<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventCategoryRequest;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Facades\Gate;

class EventCategoryController extends Controller
{
    public function index()
    {
        //Gate::authorize('viewAny', EventCategory::class);
        $cats = EventCategory::with('event')->latest()->paginate(50);
        return view('admin.event_categories.index', compact('cats'));
    }

    public function create(Event $event)
    {
        //Gate::authorize('update', $event);
        return view('admin.event_categories.create', compact('event'));
    }

    public function store(StoreEventCategoryRequest $request, Event $event)
    {
        //Gate::authorize('update', $event);
        $data = $request->validated();
        EventCategory::create($data);

        return redirect()->back()->with('success', 'Categoría agregada.');
    }

    public function edit(EventCategory $eventCategory)
    {
        //Gate::authorize('update', $eventCategory->event);
        return view('admin.event_categories.edit', ['category' => $eventCategory]);
    }

    public function update(StoreEventCategoryRequest $request, EventCategory $eventCategory)
    {
        //dd($request);
        //Gate::authorize('update', $eventCategory->event);
        $eventUpd = EventCategory::find($request->event_id);
        $data = $request->validated();
        $eventUpd->update($data);
        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy($id)
    {
        //Gate::authorize('update', $eventCategory->event);
        EventCategory::destroy($id);
        return back()->with('success', 'Categoría eliminada.');
    }
}
