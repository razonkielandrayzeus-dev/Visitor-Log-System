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

        $recentVisitors = Visitor::with('loggedByUser')
            ->orderBy('time_in', 'desc')
            ->take(10)
            ->get();

        // Last 7 days chart data
        $chartData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date' => $date->format('M d'),
                'count' => Visitor::whereDate('time_in', $date->toDateString())->count(),
            ];
        });

        $chartLabels = $chartData->pluck('date');
        $chartCounts = $chartData->pluck('count');

        return view('admin.dashboard', compact(
            'todayVisitors',
            'activeVisitors',
            'totalVisitors',
            'recentVisitors',
            'chartLabels',
            'chartCounts'
        ));
    }
}
