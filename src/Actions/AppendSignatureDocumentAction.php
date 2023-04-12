<?php

namespace Creagia\LaravelSignPad\Actions;

use Creagia\LaravelSignPad\Exceptions\InvalidConfiguration;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use Exception;
use setasign\Fpdi\Tcpdf\Fpdi;

class AppendSignatureDocumentAction
{
    /**
     * @throws InvalidConfiguration
     * @throws Exception
     */
    public function __invoke(Fpdi $pdf, string $decodedImage, SignatureDocumentTemplate $signatureTemplate): Fpdi
    {
        $this->validateConfig();

        foreach ($signatureTemplate->signaturePositions as $signaturePosition) {
            $pdf->setPage($signaturePosition->signaturePage);

            $pdf->Image(
                '@'.$decodedImage,
                $signaturePosition->signatureX,
                $signaturePosition->signatureY,
                config('sign-pad.width') * 0.26458333 / 2,
                config('sign-pad.height') * 0.26458333 / 2,
                'PNG'
            );

            if (config('sign-pad.certify_documents')) {
                // Define active area for signature
                $pdf->setSignatureAppearance(
                    $signaturePosition->signatureX,
                    $signaturePosition->signatureY,
                    config('sign-pad.width') * 0.26458333 / 2,
                    config('sign-pad.height') * 0.26458333 / 2,
                );
            }
        }

        return $pdf;
    }

    private function validateConfig(): void
    {
        if (! is_numeric(config('sign-pad.width'))) {
            throw new InvalidConfiguration("Invalid sign pad width. Check your config key 'sign-pad.width'");
        }
        if (! is_numeric(config('sign-pad.height'))) {
            throw new InvalidConfiguration("Invalid sign pad height. Check your config key 'sign-pad.height'");
        }

        if (! is_bool(config('sign-pad.certify_documents'))) {
            throw new InvalidConfiguration("Invalid boolean value. Check your config key 'sign-pad.certify_documents'");
        }
    }
}
