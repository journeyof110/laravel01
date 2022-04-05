<?php

namespace App\Services;

use App\Models\Category;
use App\Models\TimeCard;
use App\Repositories\TimeCardRepository;
use App\Services\Service;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimeCardService extends Service
{
    protected $timeCardRepository;
    protected $categoryRepository;

    /**
     * 一覧に表示する行数
     */
    const MAX_ROW = 10;

    public function __construct(
        TimeCardRepository $timeCardRepository,
        Category $categoryRepository
    )
    {
        $this->timeCardRepository = $timeCardRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * 最新の作業途中のタイムカードを取得
     *
     * @return object|null
     */
    public function working(): ?object
    {
        $latests = ['date', 'start_time'];
        $latestTimeCard = $this->timeCardRepository
            ->findLatest($latests);

        return $latestTimeCard->end_time !== null ? null : $latestTimeCard;
    }

    /**
     * タイムカードにあるデータを月ごとに取得
     *
     * @return object|null
     */
    public function getMonthOfTimeCardsByYear(): ?object
    {
        $groups = [
            'year' => 'year(date)',
            'month' => 'month(date)'
        ];
        return $this->timeCardRepository
            ->getGroupRawList($groups)
            ->whereNotNull('year')
            ->whereNotNull('month');
    }


    /**
     * 一覧表示用のタイムカードデータを取得
     *
     * @return object
     */
    public function getPageListByMonth(Carbon $current): object
    {
        $oldests = ['date', 'start_time'];
        $withs = ['category'];
        return $this->timeCardRepository
            ->getPageListByMonth(self::MAX_ROW, $oldests, $withs, $current->year, $current->month);
    }

    /**
     * プルダウンメニュー用のカテゴリーリストを取得
     *
     * @return array
     */
    public function getCategorySelectList(): array
    {
        return $this->categoryRepository->all()
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * 月ごとのページネーション用に前年と翌年のタイムカードデータを取得する
     *
     * @param Carbon $current
     * @param object $monthryTimeCards
     * @return array
     */
    public function getLastAndNextYearTimeCardDate(Carbon $current, object $monthryTimeCards): array
    {
        $years = ['following' => null, 'previous' => null];
        $followingYear = $current->subYear()->year;
        $followingYearTimeCard = $monthryTimeCards->where('year', $followingYear)->last();
        $previousYear = $current->addYear()->addYear()->year;
        $previousYearTimeCard = $monthryTimeCards->where('year', $previousYear)->first();

        if ($followingYearTimeCard) {
            $years['last'] = sprintf('%d-%02d-01', $followingYear, $followingYearTimeCard->month);
        }

        if ($previousYearTimeCard) {
            $years['previous'] = sprintf('%d-%02d-01', $previousYear, $previousYearTimeCard->month);
        }
        return $years;
    }

    /**
     * 表示するカードを設定する
     *
     * @param Request $request
     * @return array
     */
    public function getShowCollapses(Request $request): array
    {
        $shows['input'] = 'show';
        $shows['list'] = '';
        if ($request->has('page') || $request->get('show') === 'list') {
            $shows['input'] = '';
            $shows['list'] = 'show';
        }

        return $shows;
    }

    /**
     * 開始ボタンによりタイムカードデータを追加
     *
     * @param array $inputs
     * @return integer
     */
    public function addStartTimeCard(array $inputs): int
    {
        try {
            $inputs['date'] = Carbon::now();
            $inputs['start_time'] = Carbon::now();
            $inputs['end_time'] = null;
            return $this->timeCardRepository
                ->add($inputs)
                ->id;
        } catch (Exception $th) {
            Log::error(["SQL error: ", ['message' => $th->getMessage()]]);
            Log::error($th->__toString());
            throw new Exception($th->getMessage());
        }
    }

    /**
     * タイムカードデータを作成する
     *
     * @param array $inputs
     * @return object
     */
    public function addTimeCard(array $inputs): object
    {
        try {
            return $this->timeCardRepository
                ->add($inputs);
        } catch (Exception $th) {
            Log::error(["SQL error: ", ['message' => $th->getMessage()]]);
            Log::error($th->__toString());
            throw new Exception($th->getMessage());
        }
    }

    /**
     * 終了ボタンによりタイムカードデータを更新
     *
     * @param array $inputs
     * @param TimeCard $timeCard
     * @return integer
     */
    public function editEndTimeCard(array $inputs, TimeCard $timeCard): int
    {
        $now = Carbon::now();
        try {
            // 開始と終了の日付が異なる場合、レコードを分ける
            if ($timeCard->date != $now->format('Y-m-d 00:00:00')) {
                $inputs['end_time'] = '23:59';
                $inputs = $this->timeCardRepository->edit($inputs, $timeCard);

                $timeCard = $timeCard->replicate();
                $inputs['date'] = $now;
                $inputs['start_time'] = '00:00';
            }
            $inputs['end_time'] = $now->format('H:i');
            return $this->timeCardRepository
                ->edit($inputs, $timeCard)
                ->id;

        } catch (Exception $th) {
            Log::error(["SQL error: ", ['message' => $th->getMessage()]]);
            Log::error($th->__toString());
            throw new Exception($th->getMessage());
        }
    }

    /**
     * タイムカードデータを更新
     *
     * @param array $inputs
     * @param TimeCard $timeCard
     * @return object
     */
    public function editTimeCard(array $inputs, TimeCard $timeCard): object
    {
        try {
            return $this->timeCardRepository
                ->edit($inputs, $timeCard);
        } catch (Exception $th) {
            Log::error(["SQL error: ", ['message' => $th->getMessage()]]);
            Log::error($th->__toString());
            throw new Exception($th->getMessage());
        }
    }

    /**
     * タイムカードデータを削除
     *
     * @param TimeCard $timeCard
     * @return void
     */
    public function removeTimeCard(TimeCard $timeCard)
    {
        Log::debug($timeCard);
        $this->timeCardRepository
            ->remove($timeCard);
    }
}
