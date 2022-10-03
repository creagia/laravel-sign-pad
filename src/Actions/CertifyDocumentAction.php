<?php

namespace Creagia\LaravelSignPad\Actions;

use Creagia\LaravelSignPad\Exceptions\InvalidConfiguration;
use Exception;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Tcpdf\Fpdi;

class CertifyDocumentAction
{
    /**
     * @throws InvalidConfiguration
     * @throws Exception
     */
    public function __invoke(Fpdi $pdf): Fpdi
    {
        $this->validateConfig();

        $certificate = 'file://'.config('sign-pad.certificate_file');

        $pdf->setSignature(
            $certificate,
            $certificate,
            '',
            '',
            config('sign-pad.cert_type'),
            config('sign-pad.certificate_info')
        );

        return $pdf;
    }

    private function validateConfig(): void
    {
        if (! File::exists(config('sign-pad.certificate_file'))) {
            throw new InvalidConfiguration("Certificate file doesn't exists. Check your config key 'sign-pad.certificate_file'");
        }

        if (! in_array(config('sign-pad.cert_type'), [1, 2, 3])) {
            throw new InvalidConfiguration("Invalid certificate type. Check your config key 'sign-pad.cert_type'");
        }
    }
}
