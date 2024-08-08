<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipient' => 'required|string|max:255',
            'recipientName' => 'sometimes|string|max:255',
            'sender' => 'required|string|max:255',
            'senderName' => 'sometimes|string|max:255',
            'subject' => 'required|string|max:255',
            'body-html' => 'required|string',
            'Message-Id' => 'required|string|max:255',
            'In-Reply-To' => 'sometimes|required|string|max:255',
            'from' => 'sometimes|required|string|max:255',
            'From' => 'sometimes|required|string|max:255',
            'to' => 'sometimes|required|string|max:255',
            'To' => 'sometimes|required|string|max:255',
        ];
    }
}
