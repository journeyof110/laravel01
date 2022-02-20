<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TimeCard extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
    ];

    /**
     * 開始時刻の表示を修正
     *
     * @param string $value
     * @return string
     */
    public function getStartTimeAttribute($value): string|null
    {
        if (is_null($value)) {
            return null;
        }
        return Carbon::parse($value)->format('H:i');
    }

    /**
     * 終了時刻の表示を修正
     *
     * @param string $value
     * @return string
     */
    public function getEndTimeAttribute($value): string|null
    {
        if (is_null($value)) {
            return null;
        }
        return Carbon::parse($value)->format('H:i');
    }

    /**
     * 年月日を表示用に修正
     *
     * @return string
     */
    public function getDateAttribute() : string
    {
        return implode('-', $this->only('year', 'month', 'day'));
    }

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

        return Carbon::createMidnightDate($this->year, $this->month, $this->day)
            ->createFromTimeString($this->start_time)
            ->format('Y年m月d日 H時i分');
    }

    /**
     * 日にちと曜日を取得　'D日 (ddd)'
     *
     * @return string
     */
    public function getDayAndDayNameAttribute(): string
    {
        if (!isset($this->day)) {
            return '';
        }
        Carbon::setLocale('ja');
        $day = Carbon::createMidnightDate($this->year, $this->month, $this->day);
        return $day->isoFormat('D日 (ddd)');
    }

    /**
     * 日時を設定
     *
     * @param string $date
     */
    public function setDateAttribute(string $date)
    {
        $date = Carbon::parse($date);
        $this->year = $date->year;
        $this->month = $date->month;
        $this->day = $date->day;
    }

    public function scopeLatestDateTime($query): Builder
    {
        return $query->latest('year')
            ->latest('month')
            ->latest('day')
            ->latest('start_time')
            ->whereNull('end_time');
    }

    /**
     * 月によりデータを絞り込む
     *
     * @param Builder $query
     * @param integer $year
     * @param integer $month
     * @return Builder
     */
    public function scopeMonth($query, int $year, int $month): Builder
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeSortDateTime($query)
    {
        return $query->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->orderBy('start_time');
    }

    public function scopeGroupByMonth($query)
    {
        return $query->groupBy('year', 'month')->select('year', 'month');
    }


}
