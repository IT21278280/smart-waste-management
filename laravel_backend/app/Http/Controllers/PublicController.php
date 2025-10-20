<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublicController extends Controller
{
    public function index()
    {
        // Get system stats for the homepage
        $stats = $this->getSystemStats();

        return view('public.index', compact('stats'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function features()
    {
        return view('public.features');
    }

    public function contact()
    {
        return view('public.contact');
    }

    private function getSystemStats()
    {
        // Get basic stats for the homepage
        try {
            // You can customize this based on your actual data needs
            return [
                'total_users' => \App\Models\User::count(),
                'total_reports' => \App\Models\Report::count(),
                'pending_reports' => \App\Models\Report::where('status', 'pending')->count(),
                'resolved_reports' => \App\Models\Report::where('status', 'resolved')->count(),
            ];
        } catch (\Exception $e) {
            return [
                'total_users' => 0,
                'total_reports' => 0,
                'pending_reports' => 0,
                'resolved_reports' => 0,
            ];
        }
    }
}
