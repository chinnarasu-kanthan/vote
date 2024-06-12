<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassroomController extends Controller
{
    public function index()
    {
        return Classroom::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $classroom = Classroom::create($validated);

        return response()->json($classroom, 201);
    }

    public function show($id)
    {
        return Classroom::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'capacity' => 'integer|min:1',
        ]);

        $classroom->update($validated);

        return response()->json($classroom);
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return response()->json(null, 204);
    }

    public function timetables($id)
    {
        $classroom = Classroom::with('timetables')->findOrFail($id);
        return $classroom->timetables;
    }

    public function availability($id, Request $request)
    {
        $classroom = Classroom::findOrFail($id);
    $date = $request->query('date');
    
    // Get the timetable for the classroom on the specified date's day of the week
    $dayOfWeek = Carbon::parse($date)->format('l');
    $timetables = $classroom->timetables()->where('day_of_week', $dayOfWeek)->get();
    
    $availability = [];
    
    foreach ($timetables as $timetable) {
        // Calculate the available time slots based on the interval
        $start = Carbon::parse($timetable->start_time);
        $end = Carbon::parse($timetable->end_time);
        
        while ($start->lt($end)) {
            $endSlot = $start->copy()->addMinutes($timetable->interval_minutes);
            
            if ($endSlot->lte($end)) {
                // Check if this slot is available (no overlapping bookings)
                $existingBookings = $classroom->bookings()
                    ->where('date', $date)
                    ->where(function($query) use ($start, $endSlot) {
                        $query->whereBetween('start_time', [$start, $endSlot])
                              ->orWhereBetween('end_time', [$start, $endSlot])
                              ->orWhere(function($query) use ($start, $endSlot) {
                                  $query->where('start_time', '<=', $start)
                                        ->where('end_time', '>=', $endSlot);
                              });
                    })->count();
                
                if ($existingBookings == 0) {
                    $availability[] = ['start' => $start->format('H:i'), 'end' => $endSlot->format('H:i')];
                }
            }
            
            $start = $endSlot;
        }
    }

        return response()->json($availability);
    }
}