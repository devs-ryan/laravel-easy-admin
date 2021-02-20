<?php

namespace DevsRyan\LaravelEasyAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use DevsRyan\LaravelEasyAdmin\Services\ValidationService;
use Hash, Exception;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-admin:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and add a user to Easy Admin';

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

        //get required input
        $required_inputs = $this->getRequired();

        //get input
        $answers = [];
        $this->info("Fill in the users required fields..");
        foreach($required_inputs as $input) {
            $answer = $this->ask($input);
            if (in_array($answer, $this->exit_commands)) {
                $this->info("Command exit code entered.. terminating.");
                return;
            }
            if ($input == 'password') {
                $answers[$input] = Hash::make($answer);
            }
            else if ($input != 'is_easy_admin') {
                $answers[$input] = $answer;
            }
            else {
                $answers['is_easy_admin'] = in_array($answer, ['yes', 'true', 'y', '']) ? true : false;
            }
        }

        //set time stamps
        $date_time = new \DateTime();
        if ($this->hasColumn('created_at'))
            $answers['created_at'] = $date_time->format('Y-m-d H:i:s');
        if ($this->hasColumn('updated_at'))
            $answers['updated_at'] = $date_time->format('Y-m-d H:i:s');

        //create new user
        try {
            DB::table('users')->insert($answers);
            $this->info("New Easy Admin user created successfully.");
        }
        catch(Exception $e) {
            $this->info('Error inserting new record: ' . $e->getMessage());
        }
    }

    /**
     * Get required columns from user table.
     *
     * @return mixed
     */
    private function getRequired()
    {
        $required = [];

        $columns = DB::select('SHOW COLUMNS FROM users');

        foreach($columns as $column_data) {
            if ($column_data->Null == 'NO') {
                if ($column_data->Field != 'id')
                    array_push($required, $column_data->Field);
            }
        }
        return $required;
    }

    /**
     * Check if created_at exists.
     *
     * @return boolean
     */
    private function hasColumn($check_exists)
    {
        $columns = DB::select('SHOW COLUMNS FROM users');

        foreach($columns as $column_data) {
            if ($column_data->Field == $check_exists) return true;
        }
        return false;
    }
}











