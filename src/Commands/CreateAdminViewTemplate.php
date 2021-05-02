<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use DevsRyan\LaravelEasyAdmin\Services\FileService;

class CreateAdminViewTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:create-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an Easy Admin view template in resources/views/easy-admin directory';

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
    protected $fileTemplate;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $path = str_replace('/Commands', '', __DIR__).'/FileTemplates/CustomView.template';
        $this->fileTemplate = file_get_contents($path) or die("Unable to open CustomView.template file");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("<<<!!!Info!!!>>>\nAt any time enter 'q', 'quit', or 'exit' to cancel.");

        $name = $this->ask("Enter a name for your view");

        if (in_array($name, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }

        $slug = strtolower(str_replace('.blade.php', '', str_replace(' ', '-', preg_replace("/[^A-Za-z-_ ]/", '', $name))));
        $dir = resource_path("views/easy-admin");
        $path = resource_path("views/easy-admin/$slug.blade.php");

        if (file_exists($path)) {
            $this->warn("Template: resources/views/easy-admin/$slug.blade.php alread exists, choose a new name or remove it.. terminating.");
            return;
        }

        $this->info("Creating page template: resources/views/easy-admin/$slug.blade.php");

        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($path, $this->fileTemplate);

        $this->info("Easy Admin view template created successfully.");

    }
}











