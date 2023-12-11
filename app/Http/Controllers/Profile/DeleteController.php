<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\BaseController;
use App\Http\Requests\Profile\DeleteRequest;
use \Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeleteController extends BaseController
{
    public function __invoke(DeleteRequest $request)
    {
        try {
            $data = $request->validated();

            $currentPassword = $data['password'];
            $password = Auth()->user()->password;
            
            // Проверка текущего пароля
            if (!Hash::check($currentPassword, $password)) {
                return back()->withErrors(['deleteAcc' => 'The current password is not correct']);
            } else {
                // Удаление аккаунта 
                $update = $this->service->deleteAccount($data);
                return redirect()->route('home');
            }

        } catch(ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
