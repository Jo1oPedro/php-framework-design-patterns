<?php

namespace App\Requests;

use Cascata\Framework\Request\FormRequest;

class PostsCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'string|int|min:2',
            'body' => 'required',
            'bodyy' => 'required|noWhitespace'
        ];
    }

    public function messages(): array
    {
        return [
            "min" => 'O campo {{name}} deve ser maior do que 2',
            "greaterThan" => '{{name}} deve ser maior do que 2',
            "int" => 'O campo {{name}} deve ser um inteiro',
            'noWhitespace' => '{{name}} não deve conter espaços em branco',
            'required' => 'O campo {{name}} deve ser preenchido',
        ];
    }
}