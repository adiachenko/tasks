<?php

namespace App\Http\Controllers;

use App\EmailConfirmation;
use App\Notifications\ConfirmEmail;
use App\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class SendEmailConfirmationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required'
        ]);

        $user = User::where(['email' => $request->email, 'email_confirmed' => false])->firstOrFail();

        $confirmation = EmailConfirmation::updateOrCreate(['email' => $request->email], [
            'id' => Uuid::uuid4()->toString()
        ]);

        $user->notify(new ConfirmEmail($confirmation));

        return response()->json([
            'meta' => [
                'message' => 'Check your email'
            ]
        ], 202);
    }
}
