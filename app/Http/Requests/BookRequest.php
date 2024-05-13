<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country_id' => ['required', 'exists:countries,uuid'],
            'name' => ['required', 'string'],
            'email' => ['string', 'nullable', 'email'],
            'whatsapp_number' => ['string', 'nullable'],
            'surfing_experience' => ['integer'],
            'visit_date' => ['required', 'string'],
            'desired_board' => ['required', 'string'],
            'file_id_verification' => ['required', 'mimes:jpeg,jpg,png,gif,svg,pdf']
        ];
    }
}
