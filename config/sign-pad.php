<?php

return [

    'store_path' => public_path('signed-pdf'),

    'redirect_route_name' => null,

    'certify_documents' => false,

    'certificate_file' => storage_path('app/certificate.crt'),

    'certificate_info' => [
        'Name' => '',
        'Location' => '',
        'Reason' => '',
        'ContactInfo' => ''
    ],

    'cert_type' => 2

];
