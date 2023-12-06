<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{

    public function allInquiriesView(Request $request) {
        $inquiries = Inquiry::orderBy('created_at', 'desc')->get();
        return view('admin.inquiries.index', ['inquiries' => $inquiries]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ];

        $customMessages = [
            'name.required' => 'The name is required.',
            'email.required' => 'The email is required.',
            'message.required' => 'The message is required',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Inquiry::create($request->all());
        return redirect()->back()->with('success', 'Your Submission Was Successful.');

    }
}
