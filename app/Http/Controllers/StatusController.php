<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getStatuses(Request $request)
    {
        $query = Status::query();

        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $statusesQuery = clone $query;
        $statusesCount = $statusesQuery->count();

        if (!$statusesCount) {
            return response()->json(['data' => [], 'count' => 0], 200);
        }

        $limit = $request->input('limit', 10); // Default limit is 10 if not provided
        $offset = $request->input('offset', 0); // Default offset is 0 if not provided

        if ($request->input('admin') == 'true') {
            $statuses = $query->get();
        } else {
            $statuses = $query->skip($offset)->take($limit)->get();
        }

        return response()->json([
            'data' => $statuses,
            'count' => $statusesCount,
        ]);
    }
}
