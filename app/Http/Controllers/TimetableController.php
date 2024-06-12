<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        return Timetable::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'interval_minutes' => 'required|integer',
        ]);

        $timetable = Timetable::create($validated);

        return response()->json($timetable, 201);
    }

    public function show($id)
    {
        return Timetable::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);

        $validated = $request->validate([
            'classroom_id' => 'exists:classrooms,id',
            'day_of_week' => 'string',
            'start_time' => 'string',
            'end_time' => 'string',
            'interval_minutes' => 'integer',
        ]);

        $timetable->update($validated);

        return response()->json($timetable);
    }

    public function destroy($id)
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->delete();

        return response()->json(null, 204);
    }
}
