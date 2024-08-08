<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailPostRequest;
use App\Models\Customer;
use App\Models\Email;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Storage;
use Random\RandomException;
use Illuminate\Support\Facades\Mail;

class MailgunController extends Controller
{
    /**
     * @param Exception $e
     * @return JsonResponse
     */
    protected function exceptionHandler(Exception $e): JsonResponse
    {
        app('log')->debug($e);
        return new JsonResponse(['status' => 'not_ok'], 500);
    }

    /**
     * @param string $from
     * @return string
     */
    protected function getCustomerName(string $from): string
    {
        if (!empty($from)) {
            $from = (explode('<', $from))[0];
        }
        return $from ?? 'Unknown name';
    }

    /**
     * @return string
     * @throws RandomException
     */
    protected function generateOrderNumber(): string
    {
        do {
            $number = sprintf('%08d', random_int(999, 999999));
        } while (Order::where('number', $number)->first());

        return $number;
    }

    /**
     * @param EmailPostRequest $request
     * @return JsonResponse
     */
    public function store(EmailPostRequest $request): JsonResponse
    {
        try {
            // validate the request
            $validatedFields = $request->validated();

            // save the body in the local filesystem
            Storage::disk('mail-bodies')->put($request->input('signature') . '_body.html', $request->input('body-html'));

            // loop through each attachment and save them on the local filesystem
            /** @var UploadedFile $attachment */
            foreach ($request->allFiles() as $attachment) {
                Storage::disk('mail-attachments')
                    ->put(
                        path: $request->input('signature') . '_' . $attachment->getClientOriginalName(),
                        contents: $attachment->getContent()
                    );
            }

            // prepare the email model
            $email = new Email;
            $email->sender = $validatedFields['sender'];
            $email->recipient = $validatedFields['recipient'];
            $email->subject = $validatedFields['subject'];
            $email->body = $validatedFields['body-html'];
            $email->messageId = $validatedFields['Message-Id'];
            $email->inReplyTo = $validatedFields['In-Reply-To'] ?? null;
            $email->from = $validatedFields['from'] ?? ($validatedFields['From'] ?? null);
            $email->to = $validatedFields['to'] ?? ($validatedFields['To'] ?? null);

            // assign an existing or new order
            $order = null;
            if (!empty($validatedFields['In-Reply-To'])) {
                $order = (Email::where('messageId', $validatedFields['In-Reply-To'])->first())->order;
            }
            if (!$order) { // new order
                $order = new Order;
                $order->number = $this->generateOrderNumber();
                $order->status = 'open';

                // assign an existing or new customer
                $customer = Customer::where('email', $email->sender)->first();
                if (!$customer) { // new customer
                    $customer = new Customer;
                    $customer->name = $this->getCustomerName($email->from);
                    $customer->email = $email->sender;
                    $customer->save();
                }

                $order->customer()->associate($customer);
                $order->save();
            }
            $email->order()->associate($order);

            // save the email model in the DB
            $email->save();

            // log the request
            app('log')->debug($request->all());

            return new JsonResponse(['status' => 'ok']);
        }
        catch (Exception $e) {
            return $this->exceptionHandler($e);
        }
    }

    /**
     * @param EmailPostRequest $request
     * @return JsonResponse
     */
    public function sendReply(EmailPostRequest $request): JsonResponse
    {
        try {
            // validate the request
            $validatedFields = $request->validated();

            // validate the inReplyTo
            if (empty($validatedFields['In-Reply-To'])) {
                throw new Exception('The inReplyTo is missing or invalid');
            }

            // send the email
            Mail::raw($validatedFields['body-html'], function (Message $message) use ($validatedFields) {
                $message->sender($validatedFields['sender']);
                $message->from($validatedFields['sender'], $validatedFields['senderName'] ?? null);
                $message->to($validatedFields['recipient'], $validatedFields['recipientName'] ?? null);
                $message->subject($validatedFields['subject']);

                $headers = $message->getPreparedHeaders();
                $headers->addIdHeader('In-Reply-To',
                    str_replace('<', '',
                        str_replace('>', '', $validatedFields['In-Reply-To'])));
                $message->setHeaders($headers);
                $headers = $message->getHeaders();

                // prepare the email model
                $email = new Email;
                $email->sender = $validatedFields['sender'];
                $email->recipient = $validatedFields['recipient'];
                $email->subject = $validatedFields['subject'];
                $email->body = $message->getTextBody();
                $email->messageId = '<' . $headers->getHeaderBody('Message-Id')[0] . '>';
                $email->inReplyTo = '<' . $headers->getHeaderBody('In-Reply-To')[0] . '>';
                $email->from = $headers->getHeaderBody('From')
                    ? $headers->getHeaderBody('From')[0]->toString() : null;
                $email->to = $headers->getHeaderBody('To')
                    ? $headers->getHeaderBody('To')[0]->toString() : null;

                $order = (Email::where('messageId', $validatedFields['In-Reply-To'])->first())->order;
                if (!$order) {
                    throw new Exception('Order not found for the reply email. Email not sent');
                }
                $email->order()->associate($order);

                // save the email model in the DB
                $email->save();
            });

            return new JsonResponse(['status' => 'ok']);
        } catch (Exception $e) {
            return $this->exceptionHandler($e);
        }
    }
}
