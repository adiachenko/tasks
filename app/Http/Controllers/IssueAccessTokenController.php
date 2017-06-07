<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueAccessTokenController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        abort_unless(Auth::attempt(['email' => $request->email, 'password' => $request->password]), 401);

        return response()->json([
            'data' => [
                'api_token' => Auth::user()->api_token
            ]
        ], 200);
    }
}
