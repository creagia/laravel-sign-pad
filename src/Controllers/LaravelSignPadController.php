<?php

namespace Creagia\LaravelSignPad\Controllers;

use Creagia\LaravelSignPad\Actions\GenerateSignatureDocumentAction;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Creagia\LaravelSignPad\Contracts\ShouldGenerateSignatureDocument;
use Creagia\LaravelSignPad\Exceptions\ModelHasAlreadyBeenSigned;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaravelSignPadController
{
    use ValidatesRequests;

    public function __invoke(Request $request, GenerateSignatureDocumentAction $generateSignatureDocumentAction): \Illuminate\Http\RedirectResponse
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

        $model = app($modelClass)->findOrFail($validatedData['id']);

        $requiredToken = md5(config('app.key').$modelClass);
        if ($validatedData['token'] !== $requiredToken) {
            abort(403, 'Invalid token');
        }

        if ($model instanceof CanBeSigned && $model->hasBeenSigned()) {
            throw new ModelHasAlreadyBeenSigned;
        }

        $uuid = Str::uuid()->toString();
        $filename = "{$uuid}.png";
        $signature = $model->signature()->create([
            'uuid' => $uuid,
            'from_ips' => $request->ips(),
            'filename' => $filename,
            'certified' => config('sign-pad.certify_documents'),
        ]);

        Storage::disk(config('sign-pad.disk_name'))->put($signature->getSignatureImagePath(), $decodedImage);

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
