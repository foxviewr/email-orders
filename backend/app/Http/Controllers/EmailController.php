<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailPostRequest;
use App\Http\Requests\SendEmailPostRequest;
use App\Http\Requests\StoreEmailPostRequest;
use App\Http\Resources\EmailResource;
use App\Models\Customer;
use App\Models\Email;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Message;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class EmailController extends Controller
{
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
     * @throws Exception
     */
    protected function generateOrderNumber(): string
    {
        do {
            $number = sprintf('%08d', random_int(999, 999999));
        } while (Order::query()->where('number', $number)->first());

        return $number;
    }

    /**
     * @param StoreEmailPostRequest $request
     * @return EmailResource
     * @throws Exception
     */
    public function store(StoreEmailPostRequest $request): EmailResource
    {
        // assign an existing or new customer
        $customer = Customer::query()->createOrFirst(['email' => $request->input('sender')], [
            'email' => $request->input('sender'),
            'name' => $this->getCustomerName($request->input('from'))
        ]);

        // assign an existing or new order
        $order = (Email::query()->where(['messageId' => $request->input('inReplyTo')])->first())?->order;
        if (!$order) {
            $order = Order::query()->create([
                'number' => $this->generateOrderNumber(),
                'status' => 'open',
                'customer_uuid' => $customer->uuid,
            ]);
        }

        // validate the request and create email model
        $email = Email::query()->create(array_merge($request->all(), [
            'order_uuid' => $order->uuid
        ]));

        // loop through each attachment and save them on the local filesystem
        /** @var UploadedFile $attachment */
        collect($request->allFiles())->each(function ($attachment) use($email) {
            Storage::disk('mail-attachments')
                ->put(
                    path: $email->uuid . '_' . $attachment->getClientOriginalName(),
                    contents: $attachment->getContent()
                );
        });

        // log the request
        //app('log')->debug($request->all());

        return new EmailResource($email);
    }

    /**
     * @param SendEmailPostRequest $request
     * @return EmailResource
     */
    public function sendReply(SendEmailPostRequest $request): EmailResource
    {
        $email = null;

        // send the email
        Mail::raw($request->input('body'), static function (Message $message) use ($request, &$email) {
            $message->sender($request->input('sender'));
            $message->from($request->input('sender'), $request->input('senderName'));
            $message->to($request->input('recipient'), $request->input('recipientName'));
            $message->subject($request->input('subject'));

            $headers = $message->getPreparedHeaders();
            $headers->addIdHeader('In-Reply-To',
                str_replace('<', '',
                    str_replace('>', '', $request->input('inReplyTo'))
                )
            );
            $message->setHeaders($headers);
            $headers = $message->getHeaders();

            // prepare the email model
            $fields = $request->input();
            $fields['messageId'] = '<' . $headers->getHeaderBody('Message-Id')[0] . '>';
            $fields['inReplyTo'] = '<' . $headers->getHeaderBody('In-Reply-To')[0] . '>';
            $fields['from'] = $headers->getHeaderBody('From')
                ? $headers->getHeaderBody('From')[0]->toString() : null;
            $fields['to'] = $headers->getHeaderBody('To')
                ? $headers->getHeaderBody('To')[0]->toString() : null;

            // fetch the associated order
            $order = (Email::query()->where('messageId', $request->input('inReplyTo'))->first())->order;
            if (!$order) {
                throw new RuntimeException('Order not found for the reply email. Email not sent');
            }

            // associate the order
            $fields['order_uuid'] = $order->uuid;

            // create the email
            $email = Email::query()->create($fields);
        });

        return new EmailResource($email);
    }
}
