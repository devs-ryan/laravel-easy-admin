<?php

namespace Raysirsharp\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:remove-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a user from the database';
    
    /**
     * Continue Commands.
     *
     * @var array
     */
    protected $continue_commands = ['y', 'yes'];
    
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
        
        //get user input
        $user_input = $this->ask("Enter a user email or id to be removed from the database");
        if (in_array($user_input, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }
        
        //find user
        $user = DB::table('users')->where('email', $user_input)->first();
        if (!$user) {
            $user = DB::table('users')->where('id', $user_input)->first();
        }
        
        //check user found
        if (!$user) {
            $this->info("User not found with the credentials provided.. terminating.");
            return;
        }
        
        $continue = $this->ask("You are about to permanently remove a user from the database, continue? [y]es or [n]o");
        
        //continue check
        if (!in_array(strtolower($continue), $this->continue_commands)) {
             $this->info("Command exit code entered.. terminating.");
        }
        else {
            //delete user
            DB::table('users')->where('id', $user->id)->delete();
            $this->info("User was removed successfully!");
        }
        
    }
}











