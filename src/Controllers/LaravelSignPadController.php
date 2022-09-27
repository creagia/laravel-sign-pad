<?php

namespace Creagia\LaravelSignPad\Controllers;

use Creagia\LaravelSignPad\Actions\GenerateSignatureDocumentAction;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Creagia\LaravelSignPad\Contracts\ShouldGenerateSignatureDocument;
use Creagia\LaravelSignPad\Exceptions\ModelHasAlreadyBeenSigned;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LaravelSignPadController
{
    use ValidatesRequests;

    public function __invoke(Request $request, GenerateSignatureDocumentAction $generateSignatureDocumentAction)
    {
        $validatedData = $this->validate($request, [
            'model' => ['required'],
            'sign' => ['required'],
            'id' => ['required'],
            'token' => ['required'],
        ]);

        $modelClass = $validatedData['model'];
        $decodedImage = base64_decode(explode(',', $validatedData['sign'])[1]);

        if (! $decodedImage) {
            throw new Exception('Invalid signature');
        }

        /** @var CanBeSigned $model */
        $model = app($modelClass)->findOrFail($validatedData['id']);

        $requiredToken = md5(config('app.key').$modelClass);
        if ($validatedData['token'] !== $requiredToken) {
            abort(403, 'Invalid token');
        }

        if ($model->hasBeenSigned()) {
            throw new ModelHasAlreadyBeenSigned();
        }

        $uuid = Str::uuid()->toString();
        $filename = "{$uuid}.png";
        $signature = Signature::create([
            'model_type' => $model::class,
            'model_id' => $model->id,
            'uuid' => $uuid,
            'from_ips' => $request->ips(),
            'filename' => $filename,
            'certified' => config('sign-pad.certify_documents'),
        ]);

        File::ensureDirectoryExists(config('sign-pad.store_path'));
        File::put(config('sign-pad.store_path').'/'.$filename, $decodedImage);

        if ($model instanceof ShouldGenerateSignatureDocument) {
            ($generateSignatureDocumentAction)(
                $signature,
                $model->getSignatureDocumentTemplate(),
                $decodedImage
            );
        }

        return redirect()->route(config('sign-pad.redirect_route_name'), ['uuid' => $uuid]);
    }
}
