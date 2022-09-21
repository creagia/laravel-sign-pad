<?php

namespace Creagia\LaravelSignPad\Controllers;

use Creagia\LaravelSignPad\Models\PdfSignature;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LaravelSignPadController
{
    protected $pdf;

    public function __construct(TCPDF $tcpdf)
    {
        $this->pdf = $tcpdf;
    }

    public function index(Request $request)
    {
        $model_type = $request->input('model');
        $model_id = (int)$request->input('id');
        $token = $request->input('token');

        $model = app($model_type)->find($model_id);

        if ($token !== md5(config('app.key') . $model_type)) {
            abort(404);
        }

        if ($model->hasSignedDocument()) {
            abort(404);
        }

        // Set certificate file
        $certificate = 'file://' . config('sign-pad.certificate');

        // Set additional information in the signature
        $info = config('sign-pad.certificate-info');

        // Set document signature
        $this->pdf::setSignature($certificate, $certificate, 'demo', '', 1, $info);

        // Decode signature image
        $encoded_image = explode(",", $request->sign)[1];
        $decoded_image = base64_decode($encoded_image);

        $this->pdf::AddPage();

        // Print the view
        $text = view(app($model_type)->signPdfTemplate, ['model' => $model]);

        // Add view content
        $this->pdf::writeHTML($text, true, 0, true, 0);

        // Add image for signature
        $this->pdf::Image('@' . $decoded_image, "", 50, 75, "", 'PNG');

        // Define active area for signature appearance
        $this->pdf::setSignatureAppearance(10, 50, 75, 35);

        //save to the db
        $pdfSignature = PdfSignature::create(
            [
                'model_type' => $model_type,
                'model_id' => $model_id,
                'from_ips' => $request->ips()
            ]
        );

        $filename = $pdfSignature->id . '-' . rand(0, 9999) . '.pdf';
        $filename = isset($model->pdfPrefix)
            ? $model->pdfPrefix . $filename
            : 'document' . $filename;

        $pdfSignature->file = $filename;
        $pdfSignature->save();

        if (!File::isDirectory(config('sign-pad.store_path'))) {
            File::makeDirectory(config('sign-pad.store_path'), 0777, true, true);
        }
        // Save pdf file
        $this->pdf::Output(config('sign-pad.store_path') . '/' . $filename, 'F');

        return redirect(route(config('sign-pad.redirect_url')));
    }
}
