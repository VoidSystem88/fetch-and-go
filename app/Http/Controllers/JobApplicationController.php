<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class JobApplicationController extends Controller
{
    public function showForm()
    {
        return view('apply');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:job_applications',
            'phone' => 'required|string|max:20',
            'position' => 'required|in:rider,staff',
            'message' => 'nullable|string|max:1000',
        ]);

        $application = JobApplication::create($validated);

        // Send confirmation email (optional - will implement later)
        // Mail::to($application->email)->send(new ApplicationReceived($application));

        return redirect()->route('apply.form')->with('success', 
            'Thank you for your application! We have received your request. 
            Please wait for our email confirmation within 24-48 hours. 
            We will notify you once your application is reviewed.'
        );
    }

    // Admin methods
    public function index()
    {
        $applications = JobApplication::latest()->paginate(20);
        return view('admin.applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        return view('admin.applications.show', compact('application'));
    }

    public function approve(Request $request, JobApplication $application)
    {
        $user = $application->approve(auth()->id(), $request->notes);
        
        // Send approval email
        // Mail::to($application->email)->send(new ApplicationApproved($user, $request->notes));
        
        return redirect()->route('admin.applications.index')
            ->with('success', "Application approved! User account created: {$user->email}");
    }

    public function reject(Request $request, JobApplication $application)
    {
        $application->reject(auth()->id(), $request->notes);
        
        // Send rejection email
        // Mail::to($application->email)->send(new ApplicationRejected($application, $request->notes));
        
        return redirect()->route('admin.applications.index')
            ->with('success', 'Application rejected.');
    }
}