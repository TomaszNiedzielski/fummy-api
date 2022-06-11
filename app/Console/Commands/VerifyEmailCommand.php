<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class VerifyEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify user email.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        $updated = DB::table('users')
            ->where('email', $email)
            ->update([
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);

        if ($updated === 0) {
            return $this->error('User has been not found.');
        }

        return $this->info($email.' has been verified.');
    }
}
