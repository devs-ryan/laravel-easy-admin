<?php

namespace Raysirsharp\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use Raysirsharp\LaravelEasyAdmin\Services\FileService;

class ListImageSizesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:image-sizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the supported image sizes';

    /**
     * Helper Service.
     *
     * @var class
     */
    protected $fileService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileService = new FileService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->fileService->image_sizes as $name => $size) {
            $this->info($name . ': ' . $size);
        }

    }
}











