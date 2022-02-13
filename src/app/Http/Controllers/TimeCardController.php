<?php

namespace App\Http\Controllers;

use App\Models\TimeCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

class TimeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info('stert index');
        $latestTimeCard = TimeCard::latest()->first();

        $now = Carbon::now();
        $year = $request->get('year', $now->year);
        $month = $request->get('month', $now->month);
        $timeCards = TimeCard::month($year, $month)->get();

        return view('timecard.index', [
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
    public function start(Request $request)
    {
        Log::info("start start", $request->all());

        if ($request->get('hasClieckedStart') != 1) {
            return back()->withError('作業開始が正しく処理できませんでした。');
        }

        $now = Carbon::now();
        $timeCard = new TimeCard();
        $timeCard->date = $now;
        $timeCard->start_time = $now->format('H:i:s');
        $timeCard->save();

        return back()->with('success', $timeCard->start_datetime . ' 作業を開始しました。');
    }
    
    /**
     * 終了日時を設定
     *
     * @param Request $request
     * @param TimeCard $timeCard
     * @return void
     */
    public function end(Request $request, TimeCard $timeCard)
    {
        Log::info("start end", ['request' => $request->all(), 'timeCard' => $timeCard->toArray()]);

        if ($request->get('hasClieckedEnd') != 1) {
            return back()->withError('作業終了が正しく処理できませんでした。');
        }

        // 開始と終了の日付が異なる場合、レコードを分ける
        $now = Carbon::now();
        if ($timeCard->day !== $now->day) {
            $timeCard->end_time = '23:59:59';
            $timeCard->save();
            $timeCard = new TimeCard();
            $timeCard->date = $now;
            $timeCard->start_time = '00:00:00';
        }
        $timeCard->end_time = $now->format('H:i:s');
        $timeCard->save();

        return back()->with('success', $timeCard->end_datetime . ' 作業を終了しました。');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTimeCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimeCardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function show(TimeCard $timeCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeCard  $timeCard
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeCard $timeCard)
    {
        //
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
        //
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
