<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class ClearChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearchat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old messages in global chat';

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
        DB::table('global_messages')->where('created_at', '<', Carbon::now()->subMinutes(60))->delete();
    }
}
