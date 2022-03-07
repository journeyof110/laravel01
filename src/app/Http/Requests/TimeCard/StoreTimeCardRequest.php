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
            'category_id'   => 'required|exists:categories,id',
            'memo'          => 'nullable|string|max:255',
        ];
    }
}
