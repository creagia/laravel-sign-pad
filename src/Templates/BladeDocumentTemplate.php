<?php

namespace Creagia\LaravelSignPad\Templates;

use Creagia\LaravelSignPad\Signature;
use setasign\Fpdi\Tcpdf\Fpdi;

class BladeDocumentTemplate extends DocumentTemplate
{
    public function appendPages(Fpdi $pdf, Signature $signature): Fpdi
    {
        $pdf->AddPage();
        $html = view($this->path, ['model' => $signature->model]);
        $pdf->writeHTML($html, true, false, true, false);

        return $pdf;
    }
}
