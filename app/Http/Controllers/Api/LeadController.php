<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $leads = Lead::with('assignedTo')
                     ->when($request->status, fn($q) =>
                         $q->where('status', $request->status))
                     ->latest()
                     ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data'    => $leads,
        ]);
    }

    public function show(Lead $lead)
    {
        return response()->json([
            'success' => true,
            'data'    => $lead->load(['assignedTo', 'activities']),
        ]);
    }
}