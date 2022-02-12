<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
    ];

    public function getStartTimeAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        $startTime = new Carbon($value);
        return $startTime->format('Y年m月d日 H時i分s秒');
    }
    
    public function getEndTimeAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        $endTime = new Carbon($value);
        return $endTime->format('Y年m月d日 H時i分s秒');
    }

    
}
