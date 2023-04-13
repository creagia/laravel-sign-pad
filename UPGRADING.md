# Upgrading
## From v1.x to v2.x
- Delete the published Javascript assets from `public/vendor/sign-pad` and publish them again:

```php artisan vendor:publish --tag=sign-pad-assets```

- Add new config values and rename `store_path` to `signatures_path`:
```php
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
```
- Change the `SignatureDocumentTemplate` parameters to the new multiple signature positions array. For example replace:
```php
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use Creagia\LaravelSignPad\Templates\PdfDocumentTemplate;

public function getSignatureDocumentTemplate(): SignatureDocumentTemplate
{
    return new SignatureDocumentTemplate(
        signaturePage: 1,
        signatureX: 20,
        signatureY: 25,
        outputPdfPrefix: 'document', // optional
        template: new PdfDocumentTemplate(storage_path('pdf/template.pdf')),
    );
}
```
With:
```php
use Creagia\LaravelSignPad\Templates\PdfDocumentTemplate;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use Creagia\LaravelSignPad\SignaturePosition;

public function getSignatureDocumentTemplate(): SignatureDocumentTemplate
{
    return new SignatureDocumentTemplate(
        outputPdfPrefix: 'document', // optional
        template: new PdfDocumentTemplate(storage_path('pdf/template.pdf')), // Uncomment for PDF template
        signaturePositions: [
             new SignaturePosition(
                 signaturePage: 1,
                 signatureX: 20,
                 signatureY: 25,
             ),
             new SignaturePosition(
                 signaturePage: 2,
                 signatureX: 25,
                 signatureY: 50,
             ),
        ]               
    );
}
```
