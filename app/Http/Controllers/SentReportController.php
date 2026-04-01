<?php

namespace App\Http\Controllers;

use App\Models\SentReport;

class SentReportController extends Controller
{
    public function index()
    {
        $reports = SentReport::with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('reports.sent', compact('reports'));
    }
}
