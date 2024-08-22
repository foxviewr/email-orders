<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class StoreEmailPostRequest extends EmailPostRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'messageId' => 'required|string|max:255',
            'inReplyTo' => 'nullable|string|max:255',
            'from' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->replace([
            'recipient' => $this->input('recipient'),
            'sender' => $this->input('sender'),
            'subject' => $this->input('subject'),
            'body' => $this->input('body-html') ?? $this->input('bodyHtml'),
            'messageId' => $this->input('Message-Id') ?? $this->input('messageId'),
            'inReplyTo' => $this->input('In-Reply-To') ?? $this->input('inReplyTo'),
            'to' => $this->input('To') ?? $this->input('to'),
            'from' => $this->input('From') ?? $this->input('from'),
        ]);
    }
}
