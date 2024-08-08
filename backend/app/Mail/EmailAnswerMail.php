<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class EmailAnswerMail extends Mailable
{
    public $subject;
    protected $inReplyTo;

    public function __construct(string $subject, string $html, string|null $inReplyTo = null)
    {
        $this->subject = $subject;
        $this->html = $html;
        $this->inReplyTo = $inReplyTo;
    }

    public function build(): Mailable
    {
        $mailer = $this
            ->from('orders@sandbox0d18169bf73b4e75a8105f26e483d789.mailgun.org')
            ->subject($this->subject)
            ->html($this->html);

        if (!empty($this->inReplyTo)) {
            $mailer = $mailer->withHeaders(['In-Reply-To' => $this->inReplyTo]);
        }

        return $mailer;
    }
}
