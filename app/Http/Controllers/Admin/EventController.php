<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('search')) {
            $query->where('heading', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('entries', 10));

        return view('admin.knowledge-hub.events.events', [
            'mode'   => 'index',
            'events' => $events,
        ]);
    }

    public function create()
    {
        return view('admin.knowledge-hub.events.events', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading'     => 'required|string|max:500',
            'place'       => 'nullable|string|max:255',
            'event_date'  => 'nullable|date',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'alt_tag'     => 'nullable|string|max:255',
        ]);

        $data = [
            'heading'     => $request->heading,
            'place'       => $request->place,
            'event_date'  => $request->event_date,
            'description' => $request->description,
            'alt_tag'     => $request->alt_tag,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->route('admin.knowledge-hub.events.index')
                         ->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $record = Event::findOrFail($id);

        return view('admin.knowledge-hub.events.events', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'heading'     => 'required|string|max:500',
            'place'       => 'nullable|string|max:255',
            'event_date'  => 'nullable|date',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'alt_tag'     => 'nullable|string|max:255',
        ]);

        $data = [
            'heading'     => $request->heading,
            'place'       => $request->place,
            'event_date'  => $request->event_date,
            'description' => $request->description,
            'alt_tag'     => $request->alt_tag,
        ];

        if ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.knowledge-hub.events.index')
                         ->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();

        return redirect()->route('admin.knowledge-hub.events.index')
                         ->with('success', 'Event deleted successfully.');
    }
}