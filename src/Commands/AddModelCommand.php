<?php

namespace Raysirsharp\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use Exception;
use Raysirsharp\LaravelEasyAdmin\Services\FileService;

class AddModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:add-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a model to the easy admin UI';
    
    /**
     * Exit Commands.
     *
     * @var array
     */
    protected $exit_commands = ['q', 'quit', 'exit'];
    
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
        $this->FileService = new FileService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("<<<!!!Info!!!>>>\nAt any time enter 'q', 'quit', or 'exit' to cancel.");
        
        //get namespace
        $namespace = $this->ask("Enter the model namespace(EG. App\Models\)");
        if (in_array($namespace, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }
        $namespace = $this->filterInput($namespace, true);
        
        //get model
        $model = $this->ask("Enter the model name");
        if (in_array($namespace, $this->exit_commands)) {
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
        
        //check if package file has already (create otherwise)
        if ($this->FileService->checkModelExists($model_path)) {
            $this->info('Model already added to Easy Admin, checking for \App\EasyAdmin file..');
        }
        else {
            $this->FileService->addModelToList($namespace, $model);
        }
        //check if App file exists already (create otherwise)
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
        if ($input[strlen($input) - 1] != '\\' && $namespace) {
            $input .= '\\'; 
        }
        return $input;
    }
}

















