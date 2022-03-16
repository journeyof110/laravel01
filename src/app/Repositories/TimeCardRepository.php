<?php

namespace App\Repositories;

use App\Models\TimeCard;
use App\Repositories\Repository;

class TimeCardRepository extends Repository
{
    public function addTimeCard(array $inputs): object
    {
        $timeCard = new TimeCard();
        $timeCard->fill($inputs);
        $timeCard->save();

        return $timeCard;
    }

    public function editTimeCard(array $inputs, TimeCard $timeCard): object
    {
        $timeCard->fill($inputs);
        $timeCard->save();
        return $timeCard;
    }


    /**
     * 最新のタイムカードデータを取得
     *
     * @param array $latests
     * @return object
     */
    public function findLatest(array $latests): object
    {
        return TimeCard::forUser()
            ->latests($latests)
            ->first();
    }

    /**
     * 一覧表示用のタイムカードデータを取得
     *
     * @param integer $maxRow
     * @param array $oldests
     * @param array $withs
     * @return object
     */
    public function getPageList(int $maxRow, array $oldests, array $withs): object
    {
        return TimeCard::forUser()
            ->latests($oldests)
            ->withs($withs)
            ->paginate($maxRow);
    }
}
