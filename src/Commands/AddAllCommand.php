<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;
use DevsRyan\LaravelEasyAdmin\Services\FileService;
use DevsRyan\LaravelEasyAdmin\Services\HelperService;

use Illuminate\Console\Command;

class AddAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:add-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all models within app to the Easy Admin GUI';

    /**
     * Exit Commands.
     *
     * @var array
     */
    protected $exit_commands = ['q', 'quit', 'exit'];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->FileService = new FileService;
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
        if ($this->FileService->checkIsModelListCorrupted()) {
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
        $namespace_path = $this->convertNamespaceToPath($namespace);

        // scan directory for models
        $files_list = scandir(base_path() . $namespace_path);
        foreach($files_list as $filename) {

            // only add php files
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext === 'php') {
                $model = str_replace('.php', '', $filename);

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
                    continue;
                }

                //check if package file has already (create otherwise)
                if ($this->FileService->checkModelExists($model_path)) {
                    $this->info('Model already added to EasyAdmin, checking for \App\EasyAdmin file..');
                }
                else {
                    $this->FileService->addModelToList($namespace, $model);
                    $this->info('Model added to EasyAdmin models list file..');
                }
                //check if App file exists already (create otherwise)
                if ($this->FileService->checkPublicModelExists($model_path)) {
                    $this->info('\App\EasyAdmin public file already exists..');
                }
                else {
                    $this->FileService->addPublicModel($model_path);
                    $this->info('\App\EasyAdmin public file created..');
                }

                $this->info("\nModel `" . $model . "` added successfully!\n");
            }
        }

        $this->info("=====\nNOTE:\n=====\nYou must setup your pages/posts and partials manually\ninside `EasyAdmin/AppModelList.php` file using the add-all command.");
    }

    /**
     * Filter Namespace.
     *
     * @return string
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

    /**
     * Convert Namespace to path.
     *
     * @return string
     */
    private function convertNamespaceToPath($input)
    {
        // conver backslashes to forward slashes
        $input = str_replace('\\', '/', $input);

        return '/' . strtolower($input);
    }
}
