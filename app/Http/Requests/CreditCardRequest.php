<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardRequest extends FormRequest
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
            'type_cards_id' => 'required|between:1,2',
            'user_id' => 'required|integer',
            
            'billing_cycle' => 'required|integer|between:1,30',
            'closing_date' => 'required|integer|between:1,30',
            'payment_due_date' => 'required|integer|between:1,30',
        ];
    }
}
