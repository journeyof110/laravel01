<?php

namespace App\Http\Controllers;

use App\Models\TimeCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

class TimeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('stert index');
        $timeCard = TimeCard::latest()->first();
        return view('timecard.index', ['timeCard' => $timeCard]);
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

        $timeCard = TimeCard::create(['start_time' => Carbon::now()]);
        return back()->with('success', $timeCard->start_time . ' 作業を開始しました。');
    }
    
    /**
     * 終了日時を設定
     *
     * @param TimeCard $timeCard
     * @param Request $request
     * @return void
     */
    public function end(TimeCard $timeCard, Request $request)
    {
        Log::info("start end", ['request' => $request->all(), 'timeCard' => $timeCard->toArray()]);

        Log::debug("hasClieckEnd: " . $request->hasClieckedEnd);
        if ($request->get('hasClieckedEnd') != 1) {
            return back()->withError('作業終了が正しく処理できませんでした。');
        }

        $timeCard->end_time = Carbon::now();
        $timeCard->save();
        return back()->with('success', $timeCard->end_time . ' 作業を終了しました。');
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
