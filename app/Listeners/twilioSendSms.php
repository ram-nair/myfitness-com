<?php

namespace App\Listeners;

use App\Events\TwilioSms;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class twilioSendSms {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TwilioSms  $event
     * @return void
     */
    public function handle(TwilioSms $event) {
        if (!empty($event->user->mobile)) {
            $client = new Client(config('services.twilio.accountSid'), config('services.twilio.authToken'));

            // Create a notification
            try {

                if(strstr($event->user->mobile, '+1')){
                    $fromNumber = config('services.twilio.smsFromUs');
                }else{
                    $fromNumber = config('services.twilio.smsFrom');
                }

                $client->messages->create(
                        $event->user->mobile, [
                    'from' => $fromNumber,
                    'body' => $event->pushBody
                        ]
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return false;
            }
        }
        return true;
    }

}
