<?php

namespace App\Requests;

use Cascata\Framework\Request\FormRequest;

class PostsCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'optional:alpha|string',
            'body' => 'required',
            'body2' => 'required|noWhitespace'
        ];
    }

    public function messages(): array
    {
        return [
            "greaterThan" => '{{name}} deve ser maior do que 2',
            "int" => '{{name}} deve ser um inteiro',
            'noWhitespace' => '{{name}} não deve conter espaços em branco',
        ];
    }
}