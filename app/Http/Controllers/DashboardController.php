<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('guard.dashboard');
    }

    public function guardDashboard()
    {
        $activeVisitors = Visitor::whereNull('time_out')
            ->with('loggedByUser')   // <-- changed from 'guard'
            ->orderBy('time_in', 'desc')
            ->get();

        $todayCount = Visitor::whereDate('time_in', today())->count();
        $activeCount = Visitor::whereNull('time_out')->count();

        return view('guard.dashboard', compact('activeVisitors', 'todayCount', 'activeCount'));
    }

    public function adminDashboard()
    {
        $todayVisitors = Visitor::whereDate('time_in', today())->count();
        $activeVisitors = Visitor::whereNull('time_out')->count();
        $totalVisitors = Visitor::count();

        $recentVisitors = Visitor::with('loggedByUser')  // <-- changed from 'guard'
            ->orderBy('time_in', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('todayVisitors', 'activeVisitors', 'totalVisitors', 'recentVisitors'));
    }
}
