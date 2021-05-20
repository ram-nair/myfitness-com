<?php

namespace App\Listeners;

use App\Events\TwilioPush;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class twilioPushNotification {

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
     * @param  TwilioPush  $event
     * @return void
     */
    public function handle(TwilioPush $event) {
        if ($event->user->twilioIdentity != NULL) {
            $client = new Client(config('services.twilio.accountSid'), config('services.twilio.authToken'));

            $serviceSid = config('services.twilio.serviceSid');

            // Create a notification
            try {
                $notification = $client
                        ->notify->services($serviceSid)
                        ->notifications->create([
                    "identity" => $event->user->twilioIdentity,
                    "title" => $event->pushTitle,
                    "body" => $event->pushBody,
                    "sound" => 'trip_alert.aiff',
                    "data" => $event->pushData ? $event->pushData : '{"noData":"0"}'
                ]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        return true;
    }

}
