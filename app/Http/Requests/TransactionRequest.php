<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            "fee_amount" => "sometimes|integer",
            "lending" => "sometimes|string",
            "amount" => "required|numeric",
            "title" => "required|string",
            "body" => "required",
            "template" => "required",
            "type" => "required",
            "cards_id" => "required|exists:App\Models\Card,id",
            "template_id" => "required|exists:App\Models\Template,id",
            "register_Item" => "required"


        ];
    }
}
