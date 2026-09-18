<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Http\Requests\LeadRequest;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['assignedTo', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->paginate(10)->withQueryString();

        // Pipeline counts
        $pipeline = Lead::selectRaw('status, count(*) as count')
                        ->groupBy('status')
                        ->pluck('count', 'status');

        return view('leads.index', compact('leads', 'pipeline'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('leads.create', compact('users'));
    }

    public function store(LeadRequest $request)
    {
        Lead::create(array_merge(
            $request->validated(),
            ['created_by' => auth()->id()]
        ));

        return redirect()->route('leads.index')
                         ->with('success', 'Lead created successfully!');
    }

    public function show(Lead $lead)
    {
        $lead->load(['activities.user', 'assignedTo']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $users = User::where('is_active', true)->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(LeadRequest $request, Lead $lead)
    {
        $lead->update($request->validated());

        return redirect()->route('leads.index')
                         ->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')
                         ->with('success', 'Lead deleted successfully!');
    }
}