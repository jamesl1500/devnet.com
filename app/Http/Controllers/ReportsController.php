<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Reports::class);
        
        $reports = Reports::with(['user', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('reports.index', compact('reports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $validated['reporter_id'] = Auth::id();
        
        $report = Reports::create($validated);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Report submitted successfully!',
            'report' => $report
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reports $report)
    {
        $this->authorize('view', $report);
        
        $report->load(['user', 'reviewer', 'reportable']);
        
        return view('reports.show', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reports $report)
    {
        $this->authorize('update', $report);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] !== 'pending') {
            $validated['reviewed_by'] = Auth::id();
        }
        
        $report->update($validated);
        
        return redirect()->route('reports.show', $report)
            ->with('success', 'Report updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reports $report)
    {
        $this->authorize('delete', $report);
        
        $report->delete();
        
        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully!');
    }
}