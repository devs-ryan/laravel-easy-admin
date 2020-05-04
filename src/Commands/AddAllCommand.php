<?php

namespace Raysirsharp\LaravelEasyAdmin\Commands;

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
    protected $description = 'Add all models within app to the easy admin GUI';

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
        //
    }
}
