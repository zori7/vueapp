<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class DeleteGlobalMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $now = Carbon::now();

        $messages = DB::select("select * from global_messages");

        foreach($messages as $message) {

            $date = Carbon::parse($message->created_at);

            if ($date->diffInMinutes($now) > 10) {

                DB::delete("delete from global_messages where id = ?", [$message->id]);

            }

        }
        echo "done";
    }
}
