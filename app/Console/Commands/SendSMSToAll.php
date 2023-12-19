<?php

namespace App\Console\Commands;

use App\Actions\SMS;
use App\Models\User;
use Illuminate\Console\Command;

class SendSMSToAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "Start SMS Command \n";

        $message="سلام، پل استار موسسه شتابدهی استعداد";
        $phones = User::all()->pluck('phone')->toArray();
        $phonesToString = implode(",",$phones);
        SMS::sendSMSToAll($message,$phonesToString);

        echo "End SMS Command \n";
    }
}
