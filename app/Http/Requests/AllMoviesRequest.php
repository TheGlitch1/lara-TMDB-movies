<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllMoviesRequest extends FormRequest
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
            'page' => 'integer|min:1',
            'filter' => 'nullable|in:most_voted,least_voted,under_5,all',
        ];
    }
}
