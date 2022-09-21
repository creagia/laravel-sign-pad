# PDF pad signature for laravel

Laravel pad signature that generates
a [certified PDF](https://www.prepressure.com/pdf/basics/certified-pdf#:~:text=A%20Certified%20PDF%20is%20a,errors%20or%20notifications%20were%20generated)
.

## Requirements

You need to install the [szimek/signature_pad](https://github.com/szimek/signature_pad) npm package, which will be used
as the signature input:

```bash
npm install signature_pad@4.0.0 --save
```

## Installation

You can install the package via composer:

```bash
composer require creagia/laravel-sign-pad
```

Publish the js assets, the config and the migration files

```bash
php artisan vendor:publish --tag=sign-pad
```

Add the js assets to your main javascript

Open the `resources/js/app.js` file with your text editor and add the following line:

```js
import './creagia/laravel-sign-pad'
```

Execute the migrations

```bash
php artisan migrate
```

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

## Preparing your model

You should add the followind trait in the model where you want to sign a document:

```php
<?php

namespace App\Models;

use Creagia\LaravelSignPad\Traits\RequiresSignature;

class MyModel extends Model
{
    use RequiresSignature;
    
    public function getPdfTemplate():  
    {
        return view('pdf/sign-document', ['foo' => 'bar'])    
    }
}

?>
```

## Usage

At this point, all you need is to create the form with the sign pad canvas in your template. You should call the method getSignatureUrl() from the instance of the model you prepared before as a route:

```html

<form action="{{ $myModel->getSignatureRoute() }}" method="POST">
    @csrf
    <div style="text-align: center">
        <x-signature-pad
            width="500"
            height="250"
            border-color="#eaeaea"
            pad-classes=""
            button-classes=""
        />
    </div>
</form>
```
