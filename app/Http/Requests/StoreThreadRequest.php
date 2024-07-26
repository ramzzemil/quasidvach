<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreThreadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required|string'
        ];
    }
}
