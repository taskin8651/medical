<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'           => ['required', 'string', 'max:30'],
            'business_name'   => ['required', 'string', 'max:255'],
            'business_type'   => ['required', 'string', 'max:100'],
            'gst_no'          => ['nullable', 'string', 'max:20'],
            'drug_license_no' => ['required', 'string', 'max:100'],
            'address'         => ['required', 'string', 'max:1000'],
            'city'            => ['required', 'string', 'max:100'],
            'state'           => ['required', 'string', 'max:100'],
            'pincode'         => ['required', 'string', 'max:20'],
            'country'         => ['required', 'string', 'max:100'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

         /**
          * Create a new user instance after a valid registration.
          *
          * @param  array  $data
          * @return \App\User
          */
         protected function create(array $data)
         {
             return User::create([
                 'name'            => $data['name'],
                 'email'           => $data['email'],
                 'phone'           => $data['phone'],
                 'business_name'   => $data['business_name'],
                 'business_type'   => $data['business_type'],
                 'gst_no'          => $data['gst_no'] ?? null,
                 'drug_license_no' => $data['drug_license_no'],
                 'address'         => $data['address'],
                 'city'            => $data['city'],
                 'state'           => $data['state'],
                 'pincode'         => $data['pincode'],
                 'country'         => $data['country'] ?? 'India',
                 'approval_status' => 'pending',
                 'password'        => Hash::make($data['password']),
             ]);
         }
}
