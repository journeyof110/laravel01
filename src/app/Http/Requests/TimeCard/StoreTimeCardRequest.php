<?php

namespace App\Http\Requests\TimeCard;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreTimeCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'          => 'required|date_format:Y-m-d',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i',
            'category_id'   => 'required|in:' . Category::all()->implode('id', ','),
            'memo'          => 'required|string|max:255',
        ];
    }

    public function attribute()
    {
        return [
            'date' => '年月日',
            'start_time'    => '開始時間',
            'end_time'      => '終了時間',
            'category_id'   => 'カテゴリー',
            'memo'          => 'メモ',
        ];
    }
}
