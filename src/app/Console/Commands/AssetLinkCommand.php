<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssetLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'admin:assets-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "public/admin-assets" to "./resources/assets"';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (file_exists(public_path('admin-assets'))) {
           $this->error('The "public/admin-assets" directory already exists.');
           return;
        }

        $this->laravel->make('files')->link(
            admin_path('resources/assets'), public_path('admin-assets')
        );

        $this->info('The [public/admin-assets] directory has been linked.');
    }
}