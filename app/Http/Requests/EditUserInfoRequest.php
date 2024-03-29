<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditUserInfoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'min:5|confirmed',
            'password_confirmation' => 'min:5',
            'phone' => 'required'
        ];
    }
}
