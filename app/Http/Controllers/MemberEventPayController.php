<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Event;
use App\Models\User;
use App\Models\Member;
use App\Models\MemberEventPay;
use App\Models\MemberEventPaid;
use App\Http\Requests\MemberEventPayRequest;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;

class MemberEventPayController extends Controller
{
    public function create(Event $event)
    {
        $members = Member::where('group_id', $event->group_id)->get();
        return view('member_event_pays.create', compact('members','event'));
    }
    
    public function store(MemberEventPay $member_event_pay, MemberEventPayRequest $request)
    {
        $input_group_id = $request->group_id;
        $input = $request['member_event_pay'];
        $input_member_event_pays = $request->member_event_pays_array;
        foreach($input_member_event_pays as $input_member_event_pay)
        {
            $member_event_pay = new MemberEventPay();
            $member_event_pay->event_id = $input['event_id'];
            $member_event_pay->member_id = $input_member_event_pay['member_id'];
            if (!$input_member_event_pay['amount']){
                $input_member_event_pay['amount'] = 0;
            }
            $member_event_pay->amount = $input_member_event_pay['amount'];
            $member_event_pay->save();
        }
        
        $input_member_event_paids = $request->member_event_paids_array;
        foreach($input_member_event_paids as $input_member_event_paid)
        {
            $member_event_paid = new MemberEventPaid();
            $member_event_paid->event_id = $input["event_id"];
            $member_event_paid->member_id = $input_member_event_paid;
            $member_event_paid->save();
        }
        return redirect('/groups/'. $input_group_id);
        
    }
}
