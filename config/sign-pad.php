<?php

return [
    /**
     * The disk on which to store signature images. Choose one or more of
     * the disks you've configured in config/filesystems.php.
     */
    'disk_name' => env('SIGNATURES_DISK', 'local'),

    /**
     * Path where the signature images will be stored.
     */
    'signatures_path' => 'signatures',

    /**
     * Path where the documents will be stored.
     */
    'documents_path' => 'signed_documents',

    /**
     * Route name where you want to redirect users after signing.
     */
    'redirect_route_name' => null,

    /**
     * Width and height of the signature rectangle.
     */
    'width' => 600,
    'height' => 200,

    /**
     * Should certify the document signature with certificate
     */
    'certify_documents' => false,

    /**
     * Certificate path
     */
    'certificate_file' => storage_path('app/certificate.crt'),

    /**
     * Signature certificate information. Will be attached to the generated signature in the PDF file.
     */
    'certificate_info' => [
        'Name' => '',
        'Location' => '',
        'Reason' => '',
        'ContactInfo' => '',
    ],

    /**
     * Access permissions granted for this document.
     * More information: https://hooks.wbcomdesigns.com/reference/classes/tcpdf/setsignature/#parameters
     */
    'cert_type' => 2,
];
