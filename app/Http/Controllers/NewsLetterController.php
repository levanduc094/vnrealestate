<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsletter;
use Toastr;

class NewsLetterController extends Controller
{
    public function create()
    {
        // return view('newsletter.index');
    }

    public function store(Request $request)
    {
        if (!Newsletter::isSubscribed($request->email)) 
        {
            Newsletter::subscribePending($request->email);
            Toastr::success('message', 'Success');
            return redirect()->route('home')->with('success', 'Thanks For Subscribe');
        }
        Toastr::error('message', 'Error');
        return redirect()->route('home')->with('failure', 'Sorry! You have already subscribed ');
            
    }
}
