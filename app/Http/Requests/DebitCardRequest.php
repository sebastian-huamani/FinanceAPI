<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DebitCardRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'bottom_line' => 'required|between:-999999.99,99999.99',
            'name_banck' => 'required|max:255',
            'card_expiration_date' => 'required',
        ];
    }
}
    