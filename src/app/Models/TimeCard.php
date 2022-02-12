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

    /**
     * 開始日時を取得
     *
     * @return string
     */
    public function getStartDateTimeAttribute() : string
    {
        if (is_null($this->start_time)) {
            return null;
        }
        $date = [
            $this->year,
            $this->month,
            $this->day,
        ];
        $startDateTime = new Carbon(implode('-', $date) . ' ' . $this->start_time);
        return $startDateTime->format('Y年m月d日 H時i分s秒');
    }
    
    /**
     * 終了日時を取得
     *
     * @return string
     */
    public function getEndDateTimeAttribute() : string
    {
        if (is_null($this->start_end)) {
            return null;
        }
        $date = [
            $this->year,
            $this->month,
            $this->day,
        ];
        $endDateTime = new Carbon(implode('-', $date) . ' ' . $this->end_time);
        return $endDateTime->format('Y年m月d日 H時i分s秒');
    }

    /**
     * 日時を設定
     *
     * @param Carbon $date
     */
    public function setDateAttribute(Carbon $date)
    {
        // $startDateTime = new Carbon($value);
        $this->year = $date->year;
        $this->month = $date->month;
        $this->day = $date->day;
    }
}
