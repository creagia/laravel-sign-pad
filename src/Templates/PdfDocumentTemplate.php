<?php

namespace Creagia\LaravelSignPad\Templates;

use Creagia\LaravelSignPad\Signature;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfDocumentTemplate extends DocumentTemplate
{
    public function appendPages(Fpdi $pdf, Signature $signature): Fpdi
    {
        $totalPdfPages = $pdf->setSourceFile($this->path);

        foreach (range(1, $totalPdfPages) as $pageNumber) {
            $pdf->AddPage();
            $tplIdx = $pdf->importPage($pageNumber);
            $pdf->useTemplate($tplIdx, ['adjustPageSize' => true]);
        }

        return $pdf;
    }
}
