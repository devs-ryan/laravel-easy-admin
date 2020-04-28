<?php

namespace Raysirsharp\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("<<<!!!Info!!!>>>\nAt any time enter 'q', 'quit', or 'exit' to cancel.");
        
        $namespace = $this->ask("Enter the model namespace(EG. App\Models\):");
        if (in_array($namespace, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }
        
        $model = $this->ask("Enter the model name:");
        if (in_array($namespace, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }
    }
}

















