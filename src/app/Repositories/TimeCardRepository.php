<?php

namespace App\Repositories;

use App\Models\TimeCard;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Log;

class TimeCardRepository extends Repository
{
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
    public function getPageListByMonth(int $maxRow, array $oldests, array $withs, string $year, string $month): object
    {
        return TimeCard::forUser()
            ->latests($oldests)
            ->withs($withs)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->paginate($maxRow)
            ->withQueryString();
    }

    /**
     * グループごとにタイムカードデータを取得
     *
     * @param array $groups
     * @param array $wheres
     * @return object
     */
    public function getGroupRawList(array $groups, array $wheres = []): object
    {
        return TimeCard::groupRaws($groups)
            ->whereRaws($wheres)
            ->get();
    }

    /**
     * タイムカードデータを追加
     *
     * @param array $inputs
     * @return object
     */
    public function add(array $inputs): object
    {
        $timeCard = new TimeCard();
        $timeCard->fill($inputs);
        $timeCard->save();
        return $timeCard;
    }

    /**
     * タイムカードデータを更新
     *
     * @param array $inputs
     * @param TimeCard $timeCard
     * @return object
     */
    public function edit(array $inputs, TimeCard $timeCard): object
    {
        $timeCard->fill($inputs);
        $timeCard->save();
        return $timeCard;
    }

    /**
     * タイムカードを削除
     *
     * @param TimeCard $timeCard
     * @return void
     */
    public function remove(TimeCard $timeCard)
    {
        $timeCard->delete();
    }
}
