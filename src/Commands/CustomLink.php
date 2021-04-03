<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Add a custom link to Easy Admin, append "--remove" for removing a custom link';

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

        $remove_option = $this->option('remove');
        $title = $this->ask("Enter the custom link title");


        if ($remove_option) {
            //check if already exists

            $url = $this->ask("Enter the custom link url or path");

        }
        else {
            // remove from custom links
        }


    }
}











