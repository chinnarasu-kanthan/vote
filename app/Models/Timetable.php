<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;
	
	 protected $fillable = ['classroom_id', 'day_of_week', 'start_time', 'end_time', 'interval_minutes'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
