<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use DevsRyan\LaravelEasyAdmin\Services\FileService;
use Exception;


class ResetModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the Easy Admin models file';

    /**
     * Helper Service.
     *
     * @var class
     */
    protected $fileService;

    /**
     * Continue Commands.
     *
     * @var array
     */
    protected $continue_commands = ['y', 'yes'];

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
        $this->info("<<<!!!Info!!!>>>\nAt any time enter 'q', 'quit', or 'exit' to cancel.");
        $continue = $this->ask("This will reset EasyAdmin completely, continue? [y]es or [n]o");

        //continue check
        if (!in_array(strtolower($continue), $this->continue_commands)) {
            $this->warn("Command exit code entered.. terminating.");
            return;
        }

        $this->fileService->removeAppDirectory();
        $this->fileService->createAppDirectory();
        $this->fileService->resetAppModelList();
        $this->info('EasyAdmin models reset successfully.');
    }
}
















