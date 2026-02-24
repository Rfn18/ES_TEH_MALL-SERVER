<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request) {
        $query = Activity::with('causer', 'subject')->latest();

         if ($request->log_name) {
            $query->where('log_name', $request->log_name);
        }

         $logs = $query->paginate(10);

         return response()->json($logs);

    }
}
