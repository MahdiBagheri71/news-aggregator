<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Private;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sort' => [
                'nullable',
                'string',
                'in:published_at,-published_at,title,-title,created_at,-created_at',
            ],
            /**
             * @example id,title,content,description,url,published_at,author,extra_data
             */
            'fields[articles]' => [
                'nullable',
                'string',
            ],
            'filter[id]' => [
                'nullable',
                'integer',
            ],
            'filter[published_at_before]' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'filter[published_at_after]' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'filter[title]' => [
                'nullable',
                'string',
            ],
            'filter[description]' => [
                'nullable',
                'string',
            ],
            'filter[content]' => [
                'nullable',
                'string',
            ],
            'page' => [
                'nullable',
                'min:1',
                'int',
            ],
            'per_page' => [
                'nullable',
                'min:1',
                'max:50',
                'int',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
