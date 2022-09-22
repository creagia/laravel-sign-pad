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

This will copy the package assets inside the `public/vendor/sign-pad/` folder.

## Configuration

In the published config file `config/sign-pad.php` you'll be able to configure many important aspects of the package, like the route name where users will be redirected after signing the document or where do you want to store the signed documents.

## Preparing your model

Add the `RequiresSignature` trait and implement the `CanBeSigned` class to the model you would like.

You should define the location of the blade template which will be converted to a signed PDF by implementing the `getSignaturePdfTemplate` method.

Finally, you can change the prefix of the generated PDF file with the `getSignaturePdfPrefix` method.

```php
<?php

namespace App\Models;

use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;

class MyModel extends Model implements CanBeSigned
{
    use RequiresSignature;
    
    public function getSignaturePdfTemplate(): string
    {
        return 'pdf/my-pdf-blade-template';
    }

    public function getSignaturePdfPrefix(): string
    {
        return 'my-signed-pdf';
    }
}

?>
```

Take in account that an object `$model` will be automatically injected into the PDF template, so you will be able to access all the needed properties of the model.

*******

The Trait class will add some methods to your model:
```php
//returns if the document has been signed
public function hasSignedDocument(): bool {}

//returns the path of the signed document
public function getSignedDocumentPath(): string {} 
```

## Usage

At this point, all you need is to create the form with the sign pad canvas in your template. For the route of the form, you have to call the method getSignatureUrl() from the instance of the model you prepared before:

```html
@if (!$myModel->hasSignedDocument())
    <form action="{{ $myModel->getSignatureRoute() }}" method="POST">
        @csrf
        <div style="text-align: center">
            <x-creagia-signature-pad />
        </div>
    </form>
    <script src="{{ asset('vendor/sign-pad/sign-pad.min.js') }}"></script>
@endif
```

## Customizing the component

From the same template, you can change the look of the component by passing some properties:
- *width* and *height* (string or integer) for the size of the signing area
- *border-color* (hex) to change the border color of the canvas
- *pad-classes* and *button_classes* (strings) indicates which classes will have the sign area or the submit & clear buttons

An example with an app using Tailwind would be:

```html
  <x-creagia-signature-pad
      width="600"
      height="300"
      border-color="#eaeaea"
      pad-classes="rounded-xl border-2"
      button-classes="bg-gray-100 px-4 py-2 rounded-xl mt-4"
  />
```

## Certifying the PDFs

To certify your signature with TCPDF, you will have to create your own SSL certificate with OpenSSL. Otherwise you can
find the TCPDF demo certificate
here : [TCPDF Demo Certificate](https://github.com/tecnickcom/TCPDF/blob/main/examples/data/cert/tcpdf.crt)

To create your own certificate use this command :

```
cd storage/app
openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout certificate.crt -out certificate.crt
```

More information in the [TCPDF documentation](https://tcpdf.org/examples/example_052/)

After generating the certificate, you'll have to change the value of the variable `certify_documents` in the `config/sign-pad.php` file and set it to **true**. 

When the variable `certify_documents` is set to true, the package will search the file allocated in the `certificate_file` path to sign the documents. Feel free to modify the location or the name of certificate file by changing its value.

Inside the same `config/sign-pad.php` we encourage you to fill all the fields of the array `certificate_info` to be more specific with the certificate.

Finally, you can change the certificate type by modifying the value of the variable `cert_type` (by default 2). You can find more information about certificates types at [TCPDF setSignature reference](https://hooks.wbcomdesigns.com/reference/classes/tcpdf/setsignature/).