<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeletePendingRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pending request that have crossed the current time';

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
        $requests = DB::table('meetings')
            ->where('status','=','pending')
            ->get();
        foreach ($requests as $request){
            if (Carbon::now()->toDateString() > $request->date || (Carbon::now()->toDateString() == $request->date && Carbon::now()->toTimeString() >= Carbon::parse($request->time)->toTimeString())){
                DB::table('meetings')->where('id','=',$request->id)->delete();
                DB::table('user_has_meeting')->where('meetingid','=',$request->id)->delete();
            }
        }
    }
}
