<?php

namespace Creagia\LaravelSignPad\Controllers;

use App\Http\Controllers\Controller;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use function Creagia\LaravelSignPad\app\Http\Controllers\config;
use function Creagia\LaravelSignPad\app\Http\Controllers\public_path;
use function Creagia\LaravelSignPad\app\Http\Controllers\view;

class LaravelSignPadController extends Controller
{
    protected $pdf;

    public function __construct(TCPDF $tcpdf)
    {
        $this->pdf = $tcpdf;
    }

    public function sign()
    {
        return view('sign::pad');
    }

    public function index(Request $request)
    {
        // Set certificate file
        $certificate = 'file://' . config('sign-pad.certificate');

        // Set additional information in the signature
        $info = config('sign-pad.certificate-info');

        // Set document signature
        $this->pdf::setSignature($certificate, $certificate, 'demo', '', 2, $info);

        // Decode signature image
        $encoded_image = explode(",", $request->sign)[1];
        $decoded_image = base64_decode($encoded_image);

        $this->pdf::AddPage();

        // Print the view
        $text = view('sign::template/pdf');

        // Add view content
        $this->pdf::writeHTML($text, true, 0, true, 0);

        // Add image for signature
        $this->pdf::Image('@' . $decoded_image, "", 50, 75, "", 'PNG');

        // Define active area for signature appearance
        $this->pdf::setSignatureAppearance(10, 50, 75, 35);

        //save to the db



        // Save pdf file
        $this->pdf::Output(public_path('signed-document.pdf'), 'FI');
    }
}
