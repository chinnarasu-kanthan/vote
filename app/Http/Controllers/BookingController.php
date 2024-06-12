<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'number_of_people' => 'required|integer|min:1',
        ]);

        $booking = Booking::create($validated);

        return response()->json($booking, 201);
    }

    public function show($id)
    {
        return Booking::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'classroom_id' => 'exists:classrooms,id',
            'user_id' => 'exists:users,id',
            'date' => 'date',
            'start_time' => 'string',
            'end_time' => 'string',
            'number_of_people' => 'integer|min:1',
        ]);

        $booking->update($validated);

        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(null, 204);
    }
}
