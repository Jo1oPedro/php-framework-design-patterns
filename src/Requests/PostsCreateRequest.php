<?php

namespace App\Requests;

use Cascata\Framework\Request\FormRequest;

class PostsCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //'title' => 'required|between:1,3',
            'title' => 'string|contains:cascata',
            //'body' => 'required',
            //'bodyy' => 'noWhitespace|optional|between:1,2'
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
            'between' => 'O campo {{name}} deve ter valores entre: {{minValue}} e {{maxValue}}',
            'contains' => 'O campo {{name}} deve conter a palavra: {{containsValue}}'
        ];
    }

    public function fields(): array
    {
        return [
            'title' => 'titulo'
        ];
    }
}