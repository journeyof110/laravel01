<?php

namespace App\Services;

use App\Models\Category;
use App\Models\TimeCard;
use App\Repositories\TimeCardRepository;
use App\Services\Service;
use Carbon\Carbon;
use Exception;
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
     * 一覧表示用のタイムカードデータを取得
     *
     * @return object
     */
    public function getPageList(): object
    {
        $oldests = ['date', 'start_time'];
        $withs = ['category'];
        return $this->timeCardRepository
            ->getPageList(self::MAX_ROW, $oldests, $withs);
    }

    /**
     * プルダウンメニュー用のカテゴリーリストを取得
     *
     * @return object
     */
    public function getCategorySelectList(): object
    {
        return $this->categoryRepository->all()
            ->pluck('name', 'id');
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
                ->addTimeCard($inputs)
                ->id;
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
                $inputs = $this->timeCardRepository->editTimeCard($inputs, $timeCard);

                $timeCard = $timeCard->replicate();
                $inputs['date'] = $now;
                $inputs['start_time'] = '00:00';
            }
            $inputs['end_time'] = $now->format('H:i');
            return $this->timeCardRepository
                ->editTimeCard($inputs, $timeCard)
                ->id;

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
            ->removeTimeCard($timeCard);
    }
}
