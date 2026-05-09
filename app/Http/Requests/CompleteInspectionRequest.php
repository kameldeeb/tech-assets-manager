<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompleteInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'loan_id' => ['required', 'exists:loans,id'],
            'result' => [
                'required',
                'in:excellent,good,damaged,maintenance_required'
            ],
            'notes' => ['nullable', 'string'],
        ];
    }
}
