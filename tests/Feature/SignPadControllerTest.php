<?php

use Creagia\LaravelSignPad\Tests\Models\TestModel;
use Creagia\LaravelSignPad\Tests\TestClasses\TestSignature;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

it('has an URL to sign the document', function () {
    $this->post(route('sign-pad::signature'))
        ->assertStatus(Response::HTTP_FOUND);
});

it('all parameters are needed', function () {
    $this->post(route('sign-pad::signature'))
        ->assertSessionHasErrors();
});

it('validates the data', function () {
    $this->post(
        route('sign-pad::signature'),
        [
            'model' => 'TestModel',
            'sign' => app(TestSignature::class),
            'id' => 1,
            'token' => md5(config('app.key').'TestModel'),
        ]
    )
        ->assertSessionHasNoErrors()
        ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
});

it('stores the signature image and deletes', function () {
    Storage::fake(config('sign-pad.disk_name'));
    $model1 = TestModel::create();
    $model2 = TestModel::create();
    $sign = 'data:image/png;base64,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AIHRBQSs0TuofgIECBAgQKA7AQGru5YoiAABAgQIEBhdQMAavYPqJ0CAAAECBLoTELC6a4mCCBAgQIAAgdEFBKzRO6h+AgQIECBAoDsBAau7liiIAAECBAgQGF1AwBq9g+onQIAAAQIEuhMQsLpriYIIECBAgACB0QUErNE7qH4CBAgQIECgOwEBq7uWKIgAAQIECBAYXUDAGr2D6idAgAABAgS6ExCwumuJgggQIECAAIHRBQSs0TuofgIECBAgQKA7AQGru5YoiAABAgQIEBhdQMAavYPqJ0CAAAECBLoTELC6a4mCCBAgQIAAgdEFBKzRO6h+AgQIECBAoDsBAau7liiIAAECBAgQGF1AwBq9g+onQIAAAQIEuhMQsLpriYIIECBAgACB0QUErNE7qH4CBAgQIECgOwEBq7uWKIgAAQIECBAYXUDAGr2D6idAgAABAgS6ExCwumuJgggQIECAAIHRBQSs0TuofgIECBAgQKA7AQGru5YoiAABAgQIEBhdQMAavYPqJ0CAAAECBLoTELC6a4mCCBAgQIAAgdEFBKzRO6h+AgQIECBAoDsBAau7liiIAAECBAgQGF1AwBq9g+onQIAAAQIEuhMQsLpriYIIECBAgACB0QUErNE7qH4CBAgQIECgOwEBq7uWKIgAAQIECBAYXUDAGr2D6idAgAABAgS6ExCwumuJgggQIECAAIHRBQSs0TuofgIECBAgQKA7AQGru5YoiAABAgQIEBhdQMAavYPqJ0CAAAECBLoTELC6a4mCCBAgQIAAgdEFBKzRO6h+AgQIECBAoDsBAau7liiIAAECBAgQGF1AwBq9g+onQIAAAQIEuhMQsLpriYIIECBAgACB0QX+A92V6MmOrBouAAAAAElFTkSuQmCC ◀data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAADICAYAAAA0n5+2AAAAAXNSR0IArs4c6QAAEG1JREFUeF7t3U2odVUZB/C/BObMV6pBkOhAjXJgIYSRYk2CBqERUY3UCo2w1EkfkzSCqElpkG';

    $this->post($model1->getSignatureRoute(), ['sign' => $sign]);
    $this->post($model2->getSignatureRoute(), ['sign' => $sign]);

    /** @var \Creagia\LaravelSignPad\Signature $signature1 */
    $signature1 = $model1->signature;
    /** @var \Creagia\LaravelSignPad\Signature $signature2 */
    $signature2 = $model2->signature;

    Storage::disk(config('sign-pad.disk_name'))->assertExists($filePath = $signature1->getSignatureImagePath());

    $content = Storage::disk(config('sign-pad.disk_name'))->get($filePath);
    $decodedImage = base64_decode(explode(',', $sign)[1]);
    $this->assertSame($decodedImage, $content);

    $signature1->delete();
    $this->assertFileDoesNotExist($filePath);
    Storage::disk(config('sign-pad.disk_name'))->assertMissing($filePath);
    Storage::disk(config('sign-pad.disk_name'))->assertExists($signature2->getSignatureImagePath());
});
