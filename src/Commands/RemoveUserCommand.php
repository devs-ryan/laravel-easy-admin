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
    protected $signature = 'easy-admin:user {--remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a user to Easy Admin, append "--remove" for removing a user';
    
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
        $remove_option = $this->option('remove');
        $email_entered = true;
        if ($remove_option) $action = 'removed from';
        else $action = 'added to';
        
        $this->info("<<<!!!Info!!!>>>\nAt any time enter 'q', 'quit', or 'exit' to cancel.");
        
        //get user input
        $user_input = $this->ask("Enter a user email or id to be" . $action . 'Easy Admin');
        if (in_array($user_input, $this->exit_commands)) {
            $this->info("Command exit code entered.. terminating.");
            return;
        }
        
        //find user
        $user = DB::table('users')->where('email', $user_input)->first();
        if (!$user) {
            $user = DB::table('users')->where('id', $user_input)->first();
            $email_entered = false;
        }
        
        //check user found
        if (!$user) {
            $this->info("User not found with the credentials provided.. terminating.");
            return;
        }
        

        
        //update user
        if ($email_entered)
            DB::table('users')->where('email', $user_input)->update(['is_easy_admin' => !$remove_option]);
        else
            DB::table('users')->where('id', $user_input)->update(['is_easy_admin' => !$remove_option]);
        $this->info("User was updated successfully!");
        
    }
}











