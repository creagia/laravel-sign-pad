<?php

namespace Creagia\LaravelSignPad;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSignPadServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-sign-pad')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasAssets()
            ->hasViewComponent('signature-pad', SignaturePad::class)
            ->hasMigration('create_pdf_signatures_table');
    }
}
