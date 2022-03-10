<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeCard\EndTimeCardRequest;
use App\Http\Requests\TimeCard\StartTimeCardRequest;
use App\Http\Requests\TimeCard\StoreTimeCardRequest;
use App\Http\Requests\TimeCard\UpdateTimeCardRequest;
use App\Models\TimeCard;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;
use PhpParser\Node\Stmt\TryCatch;

class TimeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info('start index');
        $latestTimeCard = TimeCard::auth()->latestDateTime()->first();

        $now = Carbon::now();
        $year = $request->get('year', $now->year);
        $month = $request->get('month', $now->month);
        $timeCards = TimeCard::auth()->month($year, $month)->sortDescDateTime()->with('category')->get();
        $categories = Category::all()->pluck('name', 'id');

        return view('time_card.index', [
            'latestTimeCard' => $latestTimeCard,
            'timeCards' => $timeCards,
            'categories' => $categories,
        ]);
    }

    /**
     * 開始日時を設定
     *
     * @param Request $request
     * @return void
     */
    public function start(StartTimeCardRequest $request)
    {
        Log::info("start start", $request->all());

        if ($request->get('hasClieckedStart') != 1) {
            return $this->showError('作業開始');
        }

        try {
            $timeCard = new TimeCard();
            $timeCard->category_id = $request->get('category_id');
            $timeCard->memo = $request->get('memo');
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('作業開始');
        }

        return back()
            ->with('startId', $timeCard->id)
            ->with('success', '作業を開始しました。');
    }
    
    /**
     * 終了日時を設定
     *
     * @param Request $request
     * @param TimeCard $timeCard
     * @return void
     */
    public function end(EndTimeCardRequest $request, TimeCard $timeCard)
    {
        Log::info("start end", ['request' => $request->all(), 'timeCard' => $timeCard->toArray()]);

        if ($request->get('hasClieckedEnd') != 1) {
            return $this->showError('作業終了');
        }

        try {
            // 開始と終了の日付が異なる場合、レコードを分ける
            $now = Carbon::now();
            $timeCard->memo = $request->get('memo');
            $timeCard->category_id = $request->get('category_id');
            if ($timeCard->date !== $now->format('Y-m-d')) {
                $timeCard->end_time = '23:59';
                $timeCard->save();

                $timeCard = $timeCard->replicate();
                $timeCard->date = $now;
                $timeCard->start_time = '00:00';
            }
            $timeCard->end_time = $now->format('H:i');
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('作業終了');
        }
        
        return back()
            ->with('endId', $timeCard->id)
            ->with('success', '作業を終了しました。');
    }

    /**
     * エラー発生時の処理
     *
     * @param string $action
     * @return void
     */
    public function showError($action)
    {
        Log::info("start showError", ['action' => $action]);
        return back()->withError($action . 'について正しく処理できませんでした。');
    }

    /**
     * タイムカードデータ取得不可エラー
     *
     * @param string $action
     * @return void
     */
    public static function missingError()
    {
        Log::info("start showError");
        return back()->withError('タイムカードデータを取得できませんでした。');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info("start create");
        $categories = Category::all()->pluck('name', 'id');
        return view('time_card.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTimeCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimeCardRequest $request)
    {
        Log::info("start store", ['request' => $request->all()]);
        try {
            $timeCard = new TimeCard();
            $inputs = $request->except('_token');
            $timeCard->fill($inputs);
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('タイムカード作成');
        }

        return to_route('time_card.show', ['time_card' => $timeCard])
            ->with('success', 'タイムカードデータを作成しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function show(TimeCard $timeCard)
    {
        return view('time_card.show', ['timeCard' => $timeCard]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TimeCard $timeCard)
    {
        $categories = Category::all()->pluck('name', 'id');
        return view('time_card.edit', ['timeCard' => $timeCard, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimeCardRequest  $request
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimeCardRequest $request, TimeCard $timeCard)
    {
        Log::info("start update", ['request' => $request->all()]);
        try {
            $inputs = $request->except('_token', '_method');
            $timeCard->fill($inputs);
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('タイムカード更新');
        }

        return to_route('time_card.show', ['time_card' => $timeCard])
            ->with('success', 'タイムカードデータを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeCard $timeCard)
    {
        Log::info("start destroy", ['timeCard' => $timeCard]);
        try {
            $timeCard->delete();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('タイムカード削除');
        }
        return back()->with('success', 'タイムカードデータを削除しました。');
    }
}
