<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyReportMail;
use App\Models\ActivityLog;

class VisitorController extends Controller
{
    public function create()
    {
        return view('visitors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'purpose'   => 'required|string|max:255',
            'host_name' => 'required|string|max:255',
        ]);

        // Get IP address
        $ip = $request->ip();

        // Call ipapi.co to get location
        $location = 'Unknown';
        try {
            $response = file_get_contents("http://ipapi.co/{$ip}/json/");
            $geo = json_decode($response, true);
            if (isset($geo['city'], $geo['region'])) {
                $location = $geo['city'] . ', ' . $geo['region'];
            }
        } catch (\Exception $e) {
            $location = 'Unavailable';
        }

        $visitor = Visitor::create([
            'full_name'  => $validated['full_name'],
            'purpose'    => $validated['purpose'],
            'host_name'  => $validated['host_name'],
            'time_in'    => now(),
            'logged_by'  => auth()->id(),
            'ip_address' => $ip,
            'location'   => $location,
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'CHECK_IN',
            'description' => 'Checked in visitor: ' . $validated['full_name'] . ' (from ' . $location . ')',
        ]);

        return redirect()->route('guard.dashboard')
            ->with('success', 'Visitor checked in successfully!');
    }

    public function checkout(Visitor $visitor)
    {
        if ($visitor->time_out) {
            return back()->with('error', 'Visitor already checked out.');
        }

        $visitor->update(['time_out' => now()]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CHECK_OUT',
            'description' => 'Checked out visitor: ' . $visitor->full_name,
        ]);

        return back()->with('success', 'Visitor checked out successfully!');
    }

    public function index(Request $request)
    {
        $query = Visitor::with('loggedByUser'); // <-- fixed

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('time_in', $request->date);
        }

        if ($request->filled('purpose')) {
            $query->where('purpose', 'like', '%' . $request->purpose . '%');
        }

        $visitors = $query->orderBy('time_in', 'desc')->paginate(20);

        return view('visitors.index', compact('visitors'));
    }

    public function report(Request $request)
    {
        $date = $request->input('date', today()->toDateString());

        $visitors = Visitor::whereDate('time_in', $date)
            ->with('loggedByUser') // <-- fixed
            ->orderBy('time_in', 'desc')
            ->get();

        $totalVisitors = $visitors->count();
        $activeVisitors = $visitors->whereNull('time_out')->count();
        $completedVisits = $totalVisitors - $activeVisitors;

        return view('visitors.report', compact('visitors', 'date', 'totalVisitors', 'activeVisitors', 'completedVisits'));
    }

    public function sendReport(Request $request)
    {
        $request->validate([
            'date'  => 'required|date',
            'email' => 'required|email',
        ]);

        $date = $request->input('date');

        $visitors = Visitor::whereDate('time_in', $date)
            ->with('loggedByUser')
            ->orderBy('time_in', 'desc')
            ->get();

        $data = [
            'date'            => $date,
            'totalVisitors'   => $visitors->count(),
            'activeVisitors'  => $visitors->whereNull('time_out')->count(),
            'completedVisits' => $visitors->whereNotNull('time_out')->count(),
            'visitors'        => $visitors,
        ];

        try {
            Mail::to($request->email)->send(new DailyReportMail($data));

            \App\Models\ActivityLog::create([
                'user_id'     => auth()->id(),
                'action'      => 'REPORT_SENT',
                'description' => 'Sent daily report for ' . $date . ' to ' . $request->email,
            ]);

            return back()->with('success', '✅ Report sent successfully to ' . $request->email);
        } catch (\Exception $e) {
            return back()->with('error', '❌ Failed to send: ' . $e->getMessage());
        }
    }
}
