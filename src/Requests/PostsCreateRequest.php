<?php

namespace App\Requests;

use Cascata\Framework\Request\FormRequest;

class PostsCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|between:10,20',
            'body' => 'required|int'
        ];
    }

    public function messages(): array
    {
        return [
            "greaterThan" => '{{name}} deve ser maior do que 2'
        ];
    }
}