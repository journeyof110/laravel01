<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeCard\EndTimeCardRequest;
use App\Http\Requests\TimeCard\StartTimeCardRequest;
use App\Http\Requests\TimeCard\StoreTimeCardRequest;
use App\Http\Requests\TimeCard\UpdateTimeCardRequest;
use App\Models\TimeCard;
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
        $latestTimeCard = TimeCard::latestDateTime()->first();

        $now = Carbon::now();
        $year = $request->get('year', $now->year);
        $month = $request->get('month', $now->month);
        $timeCards = TimeCard::month($year, $month)->sortDateTime()->get();

        return view('time_card.index', [
            'latestTimeCard' => $latestTimeCard,
            'timeCards' => $timeCards,
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
            $now = Carbon::now();
            $timeCard = new TimeCard();
            $timeCard->date = $now->format('Y-m-d');
            $timeCard->start_time = $now->format('H:i:00');
            $timeCard->memo = $request->get('memo');
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('作業開始');
        }

        return back()->with('success', $timeCard->start_datetime . ' 作業を開始しました。');
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
            if ($timeCard->day !== $now->day) {
                $timeCard->end_time = '23:59:59';
                $timeCard->save();

                $timeCard = $timeCard->replicate();
                $timeCard->date = $now->format('Y-m-d');
                $timeCard->start_time = '00:00:00';
            }
            $timeCard->end_time = $now->format('H:i:00');
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('作業終了');
        }
        
        return back()->with('success', $timeCard->end_datetime . ' 作業を終了しました。');
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
        return view('time_card.create');
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
            foreach ($inputs as $key => $value) {
                $timeCard->{$key} = $value;
            }
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('タイムカード作成');
        }

        return back()->with('success', 'タイムカードデータを作成しました。');
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
        $request->session()->flash('timeCard', $timeCard);
        return view('time_card.edit', ['timeCard' => $timeCard]);
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
            $inputs = $request->except('_token');
            foreach ($inputs as $key => $value) {
                $timeCard->{$key} = $value;
            }
            $timeCard->save();
        } catch (\Throwable $th) {
            Log::error("SQL error: ", ['message' => $th->getMessage()]);
            return $this->showError('タイムカード更新');
        }

        return back()->with('success', 'タイムカードデータを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeCard $timeCard)
    {
        //
    }
}
