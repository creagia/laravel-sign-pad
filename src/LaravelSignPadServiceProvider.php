<?php

namespace Creagia\LaravelSignPad;

use Creagia\LaravelSignPad\Commands\InstallCommand;
use Creagia\LaravelSignPad\Components\SignaturePad;
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
            ->hasViews('laravel-sign-pad')
            ->hasRoute('web')
            ->hasAssets()
            ->hasViewComponent('creagia', SignaturePad::class)
            ->hasMigration('create_signatures_table')
            ->hasCommand(InstallCommand::class);
    }
}
