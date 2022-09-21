# Laravel PDF pad signature

A Laravel package to sign documents and generate
 [certified PDFs](https://www.prepressure.com/pdf/basics/certified-pdf#:~:text=A%20Certified%20PDF%20is%20a,errors%20or%20notifications%20were%20generated)
.

## Support us
[<img width="570" alt="Laradir banner" src="https://user-images.githubusercontent.com/240932/189903723-2c015907-b8c9-4ff7-b6e6-2c8cf10aea16.png">](https://laradir.com/?utm_campaign=github&utm_medium=banner&utm_term=laravel-web-mailer)

## Installation

You can install the package via composer:

```bash
composer require creagia/laravel-sign-pad
```

Publish the config and the migration files and migrate the database

```bash
php artisan sign-pad:install
```

Publish the .js assets:

```bash
php artisan vendor:publish --tag=sign-pad-assets
```

This will copy the file `sign-pad.js` inside the `public/vendor/sign-pad/` folder.

## Configuration

To certify your signature with TCPDF, you will have to create your own SSL certificate with OpenSSL. Otherwise you can
find the TCPDF demo certificate
here : [TCPDF Demo Certificate](https://github.com/tecnickcom/TCPDF/blob/main/examples/data/cert/tcpdf.crt)

To create your own certificate use this command :

```
cd storage/app
openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout certificate.crt -out certificate.crt
```

More information in the [TCPDF documentation](https://tcpdf.org/examples/example_052/)

After generating the certificate, you can modify the location or the name of the generated file by changing the variable `certificate` from
the config file `config/sign.pad.php`

You can also fill the `certificate_info` values in the same `config/sign.pad.php` to be more specific with the certificate.

********

In the published config file `config/sign-pad.php` you'll be able to configure many important aspects of the package, like the route name where users will be redirected after signing the document or where do you want to store the signed documents.

## Preparing your model

You should add a trait and two properties: `$pdfPrefix` and `$signPdfTemplate`. `$pdfPrefix` (optional) will change the name of the PDF file, and `$signPdfTemplate` will contain the location of the template which will be converted to a signed pdf:

```php
<?php

namespace App\Models;

use Creagia\LaravelSignPad\Traits\RequiresSignature;

class MyModel extends Model
{
    use RequiresSignature;
    
    public string $signPdfTemplate = 'pdf/my-pdf-blade-template';
    
    public string $pdfPrefix = 'my-signed-pdf';
}

?>
```

Take in account that an object `$model` will be automatically injected into the PDF template, so you will be able to access all the needed properties of the model.

*******

The Trait class will add 3 methods to your model:
```php
public function getSignatureRoute(): string //returns the post route for the form
public function hasSignedDocument(): bool //returns if the document has been signed
public function getSignedDocumentPath(): string //returns the path of the signed document
```

## Usage

At this point, all you need is to create the form with the sign pad canvas in your template. You should call the method getSignatureUrl() from the instance of the model you prepared before as a route:

```html
@if (!$myModel->hasSignedDocument())
    <form action="{{ $myModel->getSignatureRoute() }}" method="POST">
        @csrf
        <div style="text-align: center">
            <x-creagia-signature-pad
                    width="500"
                    height="250"
                    border-color="#eaeaea"
                    pad-classes=""
                    button-classes="bg-gray-100 px-4 py-2 rounded-xl mt-4"
            />
        </div>
    </form>
    <script src="/vendor/sign-pad/sign-pad.min.js" />
@endif
```