<?php

namespace App\Http\Requests\TimeCard;

use Illuminate\Support\Arr;

class StartTimeCardRequest extends StoreTimeCardRequest
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
            Arr::only(parent::rules(), 'memo'),
        ];
    }
}
