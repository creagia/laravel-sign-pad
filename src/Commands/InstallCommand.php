<?php

namespace Creagia\LaravelSignPad\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public function __construct()
    {
        $this->signature = 'sign-pad:install';
        $this->description = 'Install Laravel Sign Pad';

        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Publishing config file...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'sign-pad-config',
        ]);

        $this->info('Publishing migration...');

        $this->callSilently('vendor:publish', [
            '--tag' => 'sign-pad-migrations',
        ]);

        if ($this->confirm('Would you like to run the migrations now?')) {
            $this->comment('Running migrations...');

            $this->call('migrate');
        }

        if ($this->confirm('Would you like to star our repo on GitHub?')) {
            $repoUrl = 'https://github.com/creagia/laravel-sign-pad';

            if (PHP_OS_FAMILY == 'Darwin') {
                exec("open {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Windows') {
                exec("start {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Linux') {
                exec("xdg-open {$repoUrl}");
            }
        }

        $this->info('sign-pad has been installed!');
    }
}
