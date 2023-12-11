<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\BaseController;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request) 
    {
        try {
            $data = $request->validated();

            $this->service->update($data);

            return redirect()->route('profile.index');

        } catch(\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
