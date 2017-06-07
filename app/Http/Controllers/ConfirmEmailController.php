<?php

namespace App\Http\Controllers;

use App\EmailConfirmation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfirmEmailController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'email' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $confirmation = EmailConfirmation::where($request->only('id', 'email'))->firstOrFail();
            abort_if($confirmation->expired(), 403);
            $confirmation->delete();

            User::where($request->only('email'))
                ->firstOrFail()
                ->update(['email_confirmed' => true]);
        });

        return response('', 204);
    }
}
