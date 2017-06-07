<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskCompletionController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'completed' => 'required'
        ]);

        $task->update($request->only('completed'));

        return response('', 204);
    }
}
