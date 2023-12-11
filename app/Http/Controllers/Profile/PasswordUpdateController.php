<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Profile\BaseController;
use App\Http\Requests\Profile\PasswordUpdateRequest;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateController extends BaseController
{
    public function __invoke(PasswordUpdateRequest $request) 
    {
        try {
            $data = $request->validated();

            $currentPassword = $data['currentPassword'];
            $password = Auth()->user()->password;
            
            // Проверка текущего пароля
            if (!Hash::check($currentPassword, $password)) {
                return back()->withErrors(['currentPassword' => 'The current password is not correct']);
            } else {
                // Обновляем пароль, если совпадает старый
                $update = $this->service->passwordUpdate($data);

                if ($update) session()->flash('success', 'Password changed!');
                else session()->flash('error', 'Operation error! Try again.');
                
                return redirect()->route('profile.settings');
            }

        } catch(ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
