<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseRequest extends FormRequest
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
            'name'=>'required|string|max:255',
            'category_id'=>'required|exists:categories,id',
            'duration'=>'required|string|max:255',
            'weight'=>'nullable|numeric|min:0|required_if:category_id,!2',
            'reps'=>'nullable|integer|min:0|required_if:category_id,!2',
            'goal'=>'nullable|string|max:255',
        ];
    }
}
