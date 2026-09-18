<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['user', 'subject'])->latest('activity_date');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->paginate(15)->withQueryString();

        $users = \App\Models\User::where('is_active', true)->get();

        $summary = [
            'total'    => Activity::count(),
            'calls'    => Activity::where('type', 'call')->count(),
            'meetings' => Activity::where('type', 'meeting')->count(),
            'emails'   => Activity::where('type', 'email')->count(),
        ];

        return view('activities.index', compact('activities', 'users', 'summary'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_type'  => 'required|in:customer,lead',
            'subject_id'    => 'required|integer',
            'type'          => 'required|in:call,email,meeting,follow_up,note',
            'activity_date' => 'required|date',
            'description'   => 'required|string|max:1000',
        ]);

        $modelClass = $request->subject_type === 'customer'
            ? \App\Models\Customer::class
            : \App\Models\Lead::class;

        Activity::create([
            'user_id'       => auth()->id(),
            'subject_type'  => $modelClass,
            'subject_id'    => $request->subject_id,
            'type'          => $request->type,
            'activity_date' => $request->activity_date,
            'description'   => $request->description,
        ]);

        return back()->with('success', 'Activity logged successfully!');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activity deleted.');
    }
}