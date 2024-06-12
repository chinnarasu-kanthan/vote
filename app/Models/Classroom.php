<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
	
	protected $fillable = ['name', 'capacity'];

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
