<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTopicRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            /* Rule::unique('topics')->ignore($this->topic->id) ignores the name of topic being renamed,
            so it's possible to assign the same name (just in case) */
            'name' => ['required', 'string', Rule::unique('topics')->ignore($this->topic)],
        ];
    }
}
