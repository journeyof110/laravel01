<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\Card;

class TimeCard extends Model
{
    use SoftDeletes;

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_time' => 'datetime:H-i',
        'end_time' => 'datetime:H-i',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'date',
        'start_time',
        'end_time',
        'memo',
    ];

    public function __construct()
    {
        Carbon::setLocale('ja');
        $this->user_id = Auth::id();
    }

    /**
     * 開始時刻を取得
     *
     * @return Attribute
     */
    public function startTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? null : Carbon::parse($value)->format('H:i'),
        );
    }

    /**
     * 終了時刻を取得
     *
     * @return Attribute
     */
    public function endTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? null :Carbon::parse($value)->format('H:i'),
        );
    }

    
    /**
     * 日にちと曜日を取得　'D日 (ddd)'
     *
     * @return Attribute
     */
    public function dayAndDayNameFormat(): Attribute
    {
        return  Attribute::make(
            get: fn () => Carbon::parse($this->date)->isoFormat('D日 (ddd)'),
        );
    }

    /**
     * 日付と曜日を取得 'Y年M月D日(ddd)'
     *
     * @return Attribute
     */
    public function dateFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->date)
                ->isoFormat('Y年M月D日(ddd)')
        );
    }

    /**
     * 開始時刻を取得 'H時m分'
     *
     * @return Attribute
     */
    public function startTimeFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::createFromTimeString($this->start_time)
                    ->format('H時i分')
        );
    }

    public function endTimeFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::createFromTimeString($this->end_time)
                    ->format('H時i分')
        );
    }

    /**
     * 開始日時を取得 'Y年M月D日(ddd) H時mm分'
     *
     * @return Attribute
     */
    public function dateAndStartTimeFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf(
                '【 開始 】%s %s',
                $this->date_format,
                $this->start_time_format
            ),
        );
    }

    /**
     * 日にちと時刻を表示
     *
     * @return Attribute
     */
    public function dateAndTimeFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf(
                '%s 【開始】%s  【終了】%s',
                $this->date_format,
                $this->start_time,
                $this->end_time
            ),
        );
    }

    /**
     * タイムデータが含まれているカテゴリーの取得
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * ユーザーIDによる絞り込み
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeForUser($query, bool $forUser = true): Builder
    {
        return (!$forUser) ? : $query->where('user_id', Auth::id());
    }

    /**
     * 最新順に並び替え
     *
     * @param Builder $query
     * @param array $columns
     * @return Builder
     */
    public function scopeLatests($query, array $columns)
    {
        foreach ($columns as $column) {
            $query = $query->latest($column);
        }
        return $query;
    }

    /**
     * 最古順に並び替え
     *
     * @param Builder $query
     * @param array $sorts
     * @return Builder
     */
    public function scopeOldests($query, array $columns)
    {
        foreach ($columns as $column) {
            $query = $query->oldest($column);
        }
        return $query;
    }

    /**
     * 子テーブルを設定
     *
     * @param Builder $query
     * @param array $withs
     * @return Builder
     */
    public function scopeWiths($query, array $withs)
    {
        foreach ($withs as $with) {
            $query = $query->with($with);
        }
        return $query;
    }

    // /**
    //  * 月によりデータを絞り込む
    //  *
    //  * @param Builder $query
    //  * @param integer $year
    //  * @param integer $month
    //  * @return Builder
    //  */
    // public function scopeMonth($query, int $year, int $month): Builder
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month);
    // }

    // public function scopeGroupByMonth($query)
    // {
    //     return $query->groupBy('year', 'month')->select('year', 'month');
    // }
}
