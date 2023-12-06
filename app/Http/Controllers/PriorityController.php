<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
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

    public function getPriorities(Request $request)
    {
        $query = Priority::query();

        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $prioritiesQuery = clone $query;
        $prioritiesCount = $prioritiesQuery->count();

        if (!$prioritiesCount) {
            return response()->json(['data' => [], 'count' => 0]);
        }

        $limit = $request->input('limit', 10); // Default limit is 10 if not provided
        $offset = $request->input('offset', 0); // Default offset is 0 if not provided

        if ($request->input('admin') == 'true') {
            $priorities = $query->get();
        } else {
            $priorities = $query->skip($offset)->take($limit)->get();
        }

        return response()->json([
            'data' => $priorities,
            'count' => $prioritiesCount,
        ]);
    }
}
