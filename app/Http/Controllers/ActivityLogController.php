<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        $school = Auth::user()->currentSchool;
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux journaux d\'activité.');
        }
        
        $activities = ActivityLog::where(function($query) use ($school) {
            $query->whereHasMorph('model', [Student::class, Payment::class], function($q) use ($school) {
                $q->where('school_id', $school->id);
            });
        })
        ->with(['user', 'model'])
        ->latest()
        ->paginate(20);

        return view('activity-logs.index', compact('activities'));
    }

    public function show(ActivityLog $activityLog)
    {
        $school = Auth::user()->currentSchool;
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux journaux d\'activité.');
        }
        
        // Vérifier si l'activité appartient à l'école actuelle
        if ($activityLog->model && method_exists($activityLog->model, 'school_id')) {
            if ($activityLog->model->school_id !== $school->id) {
                abort(403);
            }
        }

        return view('activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $school = Auth::user()->currentSchool;
        
        if (!$school) {
            return redirect()->route('schools.select')
                ->with('error', 'Veuillez sélectionner une école pour accéder aux journaux d\'activité.');
        }
        
        // Vérifier si l'activité appartient à l'école actuelle
        if ($activityLog->model && method_exists($activityLog->model, 'school_id')) {
            if ($activityLog->model->school_id !== $school->id) {
                abort(403);
            }
        }

        $activityLog->delete();
        return redirect()->route('activity-logs.index')
            ->with('success', 'L\'activité a été supprimée avec succès.');
    }
} 