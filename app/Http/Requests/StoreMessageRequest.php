<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'reply_to' => 'numeric|nullable'
        ];
    }
}
