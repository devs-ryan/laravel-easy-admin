<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use DevsRyan\LaravelEasyAdmin\Services\FileService;

class CustomLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:custom-link {--remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a custom link to Easy Admin (OPTIONS: --remove)';

    /**
     * Exit Commands.
     *
     * @var array
     */
    protected $exit_commands = ['q', 'quit', 'exit'];

    /**
     * File Service.
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

        //check AppModelList corrupted
        if ($this->fileService->checkIsModelListCorrupted()) {
            $this->info("App\EasyAdmin\AppModelList.php is corrupt.\nRun php artisan easy-admin:reset or correct manually to continue.");
            return;
        }

        $this->info("<<<!!!Info!!!>>>\nAt any time enter 'q', 'quit', or 'exit' to cancel.");

        $remove_option = $this->option('remove');
        $title = $this->ask("Enter the custom link title");


        if ($remove_option) {
            $this->fileService->removeCustomLink($title);
            $action = 'removed';
        }
        else {
            $url = $this->ask("Enter the custom link url or path");
            $created = $this->fileService->addOrUpdateCustomLink($title, $url);
            $action = $created ? 'created' : 'updated';
        }

        $this->info("Custom link " . $action . " successfully.");

    }
}











