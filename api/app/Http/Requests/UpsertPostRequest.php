<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'headline' => 'required|string',
            'content' => 'required|string',
            'publish_at' => 'sometimes|required|date',
            'cover_photo' => 'sometimes|required|file',
        ];
    }
}
