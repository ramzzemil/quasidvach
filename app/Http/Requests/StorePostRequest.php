<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'reply_to' => 'numeric|nullable',
            'parent_id' => 'required|numeric',
            'parent_type' => 'required|string',
        ];
    }
}
