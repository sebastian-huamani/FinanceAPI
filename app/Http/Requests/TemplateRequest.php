<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
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
            'title' => 'required|max:255',
            'body' => 'required',
            'state' => 'required|integer|between:0,1',
            // paramer como created_at y updated_at so  n manejados por en Templatecontroller
        ];
    }
}
