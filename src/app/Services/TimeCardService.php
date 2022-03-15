<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\TimeCardRepository;
use App\Services\Service;

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
}
