<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users,email,' . request()->route('user')->id,
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
            'business_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'business_type' => [
                'nullable',
                'string',
                'max:100',
            ],
            'gst_no' => [
                'nullable',
                'string',
                'max:20',
            ],
            'drug_license_no' => [
                'nullable',
                'string',
                'max:100',
            ],
            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'state' => [
                'nullable',
                'string',
                'max:100',
            ],
            'pincode' => [
                'nullable',
                'string',
                'max:20',
            ],
            'country' => [
                'nullable',
                'string',
                'max:100',
            ],
            'approval_status' => [
                'nullable',
                'in:pending,approved,rejected',
            ],
            'password' => [
                'nullable',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
        ];
    }
}
