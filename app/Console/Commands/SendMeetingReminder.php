<?php

namespace App\Console\Commands;

use App\Mail\MeetingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendMeetingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send meeting reminder emails to users';
    private $meetings = null;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->meetings = DB::table('meetings')
            ->join('user_has_meeting', 'user_has_meeting.meetingid', '=', 'meetings.id')
            ->join('users', 'user_has_meeting.userid', '=', 'users.id')
            ->where('meetings.status', '=', 'accepted')
            ->select('meetings.date as date','meetings.id as id', 'meetings.time as time', 'meetings.day as day', 'user_has_meeting.reminders as reminders', 'users.email as email','users.name as name', 'users.id as userid')
            ->get();
        foreach ($this->meetings as $meeting){
            $with = array();
            $query = DB::table('user_has_meeting')
                ->join('users', 'users.id','=','user_has_meeting.userid')
                ->where('user_has_meeting.userid','!=',$meeting->userid)
                ->where('user_has_meeting.meetingid','=',$meeting->id)
                ->select('users.name as name')
                ->get();
            foreach ($query as $participant){
                $with[] = $participant->name;
            }
            $meeting->with = implode(',',$with);
            $meeting->start = Carbon::createFromFormat('Y-m-d H:i', $meeting->date.' '.explode('-',$meeting->time)[0]);
            $meeting->end = Carbon::createFromFormat('Y-m-d H:i', $meeting->date.' '.explode('-',$meeting->time)[1]);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->meetings->each(function($meeting) {
            if ((Carbon::now()->diffInMinutes($meeting->start, false) <= 30) && (Carbon::now()->diffInMinutes($meeting->start, false) > 0) && $meeting->reminders < 1){
                $meeting->difference = $meeting->start->diffInMinutes(Carbon::now());
                $email = new MeetingReminder($meeting);
                Mail::to('fahadcreed@gmail.com')->send($email);
                DB::table('user_has_meeting')
                    ->where('userid','=',$meeting->userid)
                    ->update(['reminders'=>$meeting->reminders + 1]);
            }
        });
    }
}
