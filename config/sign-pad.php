<?php

return [

    'certificate' => storage_path('app/certificate.crt'),

    'store_path' => public_path('signed-pdf'),

    'redirect_route_name' => null,

    'certificate_info' => [
        'Name' => '',
        'Location' => '',
        'Reason' => '',
        'ContactInfo' => ''
    ]

];
