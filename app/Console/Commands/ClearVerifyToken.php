<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearVerifyToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-verifies-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush expired verify account tokens';

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
     * @return int
     */
    public function handle(): int
    {
        DB::table('verify_accounts')->whereRaw('TIMEDIFF(MINUTE(now()), MINUTE(created_at)) > 15')->delete();
        return true;
    }
}
