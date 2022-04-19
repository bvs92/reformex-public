<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2|max:255',
            'body' => 'required|min:2'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Este obligatoriu un :attribute.',
            'body.required'  => 'Este obligatoriu :attribute.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'TITLUL',
            'body'  => 'CONTINUTUL'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title' => Str::slug($this->title),
        ]);
    }


}
