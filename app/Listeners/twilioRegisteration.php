<?php

namespace App\Listeners;

use App\Events\TwilioRegister;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class twilioRegisteration
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TwilioRegister  $event
     * @return void
     */
    public function handle(TwilioRegister $event)
    {
        $client = new Client(config('services.twilio.apiKey'), config('services.twilio.apiSecret'), config('services.twilio.accountSid'));

        $serviceSid = config('services.twilio.serviceSid');

        $service = $client->notify->v1->services($serviceSid);

        // Create a binding
        try {
            if ($event->deleteBinding) {
                $twilio = new Client(config('services.twilio.accountSid'), config('services.twilio.authToken'));
                $bindings = $twilio->notify->v1->services($serviceSid)
                    ->bindings
                    ->read(["identity" => $event->user->twilioIdentity]);

                foreach ($bindings as $record) {
                    $twilio->notify->v1->services($serviceSid)
                        ->bindings($record->sid)
                        ->delete();
                }
            }
            $binding = $service->bindings->create(
                $event->user->twilioIdentity, $event->bindingType, $event->user->deviceId
            );
        } catch (\Exception $e) {
            Log::error('Error creating binding: ' . $e->getMessage());
        }
        return;
    }

}
