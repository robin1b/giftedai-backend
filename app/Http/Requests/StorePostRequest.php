<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => '|required|string|max:255|min:5',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|max:5120',
            // 'tags' => 'sometimes|array',
            // 'tags.*' => 'string'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Titel is verplicht.',
            'title.string' => 'Titel moet een valid string zijn.',
            'title.max' => 'Titel mag niet langer zijn dan :max tekens.',
            'title.min' => 'Titel moet minstens :min tekens lang zijn.',
            'content.required' => 'de contentfield is verplicht.',
            'content.string' => 'The content moet een valid string string.'
        ];
    }
}
