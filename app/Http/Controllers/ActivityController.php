<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject_type'  => 'required|in:customer,lead',
            'subject_id'    => 'required|integer',
            'type'          => 'required|in:call,email,meeting,follow_up,note',
            'activity_date' => 'required|date',
            'description'   => 'required|string|max:1000',
        ]);

        // Map subject_type to model class
        $modelClass = $request->subject_type === 'customer'
            ? \App\Models\Customer::class
            : \App\Models\Lead::class;

        Activity::create([
            'user_id'      => auth()->id(),
            'subject_type' => $modelClass,
            'subject_id'   => $request->subject_id,
            'type'         => $request->type,
            'activity_date'=> $request->activity_date,
            'description'  => $request->description,
        ]);

        return back()->with('success', 'Activity logged successfully!');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activity deleted.');
    }
}