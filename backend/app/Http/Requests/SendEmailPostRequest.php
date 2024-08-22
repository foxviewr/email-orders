<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class SendEmailPostRequest extends EmailPostRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'recipientName' => 'sometimes|string|max:255',
            'senderName' => 'nullable|sometimes|required|string|max:255',
            'messageId' => 'nullable|sometimes|required|string|max:255',
            'inReplyTo' => 'required|string|max:255',
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->replace([
            'recipient' => $this->input('recipient'),
            'recipientName' => $this->input('recipientName'),
            'sender' => $this->input('sender'),
            'senderName' => $this->input('senderName'),
            'subject' => $this->input('subject'),
            'body' => $this->input('body-html') ?? $this->input('bodyHtml'),
            'messageId' => $this->input('Message-Id') ?? $this->input('messageId'),
            'inReplyTo' => $this->input('In-Reply-To') ?? $this->input('inReplyTo'),
        ]);
    }
}
