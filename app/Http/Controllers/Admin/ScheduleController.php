<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('requester_email', 'like', '%' . $request->search . '%')
                  ->orWhere('requester', 'like', '%' . $request->search . '%');
            });
        }

        $schedules = $query->orderBy('created_at', 'desc')
                   ->paginate(request('entries', 10))
                   ->appends(request()->query());

        return view('admin.schedules.index', compact('schedules'));
    }
}