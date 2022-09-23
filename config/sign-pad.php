<?php

return [
    'store_path' => storage_path('signed-pdf'),
    'redirect_route_name' => null,
    'width' => 600,
    'height' => 200,

    'certify_documents' => false,
    'certificate_file' => storage_path('app/certificate.crt'),
    'certificate_info' => [
        'Name' => '',
        'Location' => '',
        'Reason' => '',
        'ContactInfo' => ''
    ],
    'cert_type' => 2,
];
