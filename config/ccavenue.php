<?php

return [
    'applicationName' => 'Provis Payment',
    'merchantAccount' => 'Provis',
    'paymentUrl' => (env('ADYEN_ENV') == 'production') ? 'https://login.ccavenue.ae/apis/servlet/DoWebTrans' : 'https://login.ccavenue.ae/apis/servlet/DoWebTrans',
    'accessCode' => 'AVGH03HH50AH22HGHA',
    'accessKey' => (env('ADYEN_ENV') == 'production') ? '75D1B0504BE3DA3D8052FB5EE8CB23F8' : '75D1B0504BE3DA3D8052FB5EE8CB23F8',
    'initialPrice' => 0,
    'currency' => 'AED',
];
