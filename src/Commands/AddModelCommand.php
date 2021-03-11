<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use DevsRyan\LaravelEasyAdmin\Services\FileService;
use DevsRyan\LaravelEasyAdmin\Services\HelperService;
use Exception;

class AddModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:add-model {--page} {--post} {--partial}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a model to the Easy Admin UI';

    /**
     * Exit Commands.
     *
     * @var array
     */
    protected $exit_commands = ['q', 'quit', 'exit'];

    /**
     * Confirm Commands.
     *
     * @var array
     */
    protected $confirm_commands = ['y', 'yes'];

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

        //get namespace
        if (env('EASY_ADMIN_DEFAULT_NAMESPACE', false)) {
            $namespace = 'App\Models';
        }
        else {
            $namespace = $this->ask("Enter the model namespace(Default: App\Models\)");
            if (in_array($namespace, $this->exit_commands)) {
                $this->info("Command exit code entered.. terminating.");
                return;
            }
            if ($namespace == '') $namespace = 'App\Models';
        }
        $namespace = $this->filterInput($namespace, true);

        //get model
        $model = $this->ask("Enter the model name");
        if (in_array($model, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }
        $model = $this->filterInput($model);

        //check if model/namespace is valid
        $model_path = $namespace . $model;
        $this->info('Adding Model to Easy Admin..' . $model_path);
        if (!class_exists($model_path)) {
            $this->info('Model does not exist.. terminating.');
            return;
        }

        //check if model has `id` column
        if (!$this->helperService->checkModelHasId($model_path)) {
            $this->info('This version of East Admin only supports models with the `id` field present.. terminating.');
            return;
        }

        //check if package file has already (remove if it has)
        if ($this->fileService->checkModelExists($model_path)) {
            $this->fileService->removeModelFromList($namespace, $model);
        }

        // add and pass different model types
        if ($this->option('page') || $this->option('post')) {
            $this->fileService->addModelToList($namespace, $model, ($this->option('page') ? 'page' : 'post'));
            $this->info('Model added to EasyAdmin models list file, and marked as a ' . ($this->option('page') ? 'page' : 'post') . '..');
        }
        else if ($this->option('partial')) {
            $belongs_to_page = $this->ask("Does this partial belong to a page, post, or other partial? [y]es or [n]o");

            if (in_array($belongs_to_page, $this->confirm_commands)) {
                $belongs_to_page = $this->ask("Page, post, or partial model name this partial blongs to? (without path: eg. `HomePage`)");
                $this->fileService->addModelToList($namespace, $model, 'partial', $belongs_to_page);
            }
            else {
                $this->fileService->addModelToList($namespace, $model, 'partial', 'Global');
            }
        }
        else {
            $this->fileService->addModelToList($namespace, $model);
            $this->info('Model added to EasyAdmin models list file..');
        }

        //check if App file exists already (create otherwise)
        if ($this->fileService->checkPublicModelExists($model_path)) {
            $this->info('\App\EasyAdmin public file already exists..');
        }
        else {
            $this->fileService->addPublicModel($model_path);
            $this->info('\App\EasyAdmin public file created..');
        }

        $this->info('Model added successfully!');
    }

    /**
     * Filter Namespace.
     *
     * @return mixed
     */
    private function filterInput($input, $namespace = false)
    {
        $input = preg_replace('/\s+/', '', $input);
        $input = str_replace('/', '\\', $input);
        $input = preg_replace('/(\\\\)+/', '\\', $input);

        //add trailing slash to namespace if not included
        if ($input != '' && $input[strlen($input) - 1] != '\\' && $namespace) {
            $input .= '\\';
        }
        return $input;
    }
}

















