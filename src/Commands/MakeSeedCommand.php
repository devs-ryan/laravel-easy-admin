<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;
use DevsRyan\LaravelEasyAdmin\Services\FileService;
use DevsRyan\LaravelEasyAdmin\Services\HelperService;

use Illuminate\Console\Command;

class MakeSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:make-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a seed file for all EasyAdmin models added';

    /**
     * Continue Commands.
     *
     * @var array
     */
    protected $continue_commands = ['y', 'yes'];

    /**
     * File Service.
     *
     * @var class
     */
    protected $fileService;

    /**
     * Helper Service.
     *
     * @var class
     */
    protected $helperService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileService = new FileService;
        $this->helperService = new HelperService;
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
        $continue = $this->ask("This will reset EasyAdmin completely, continue? [y]es or [n]o");

        //continue check
        if (!in_array(strtolower($continue), $this->continue_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }

        //generate seeds

    }
}











