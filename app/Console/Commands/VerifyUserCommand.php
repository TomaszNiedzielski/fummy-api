<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class VerifyUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {nick}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nick = $this->argument('nick');

        $updated = DB::table('users')
            ->where('nick', $nick)
            ->update(['is_verified' => true]);

        if ($updated === 0) {
            return $this->error('User has been not found.');
        }

        return $this->info($nick.' has been verified.');
    }
}
