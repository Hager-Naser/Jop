<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JopUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255|string',
            'category' => 'required|integer',
            'jobtype' => 'required|integer',
            'vacancy' => 'required|integer|max:11',
            'salary' => 'string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required',
            'benefits' => '',
            'responsibility' => '',
            'qualifications' => '',
            'keywords' => 'required',
            'company_name' => 'required|string|max:255',
            'company_location' => 'string|max:255',
            'company_website' => '|string|max:255',

        ];
    }
}
