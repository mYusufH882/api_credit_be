<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        $user = UserDetail::with('user')->get();

        return $this->sendResponse(UserResource::collection($user), 'List of User.');
    }

    public function show($id): JsonResponse
    {
        $user = UserDetail::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found!!', []);
        }

        return $this->sendResponse(new UserResource($user), 'Detail User.');
    }

    public function store(Request $request): JsonResponse
    {
        $namaKtp = '';
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->hasFile('img_KTP')) {
            $request->validate([
                'img_KTP' => 'image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $namaKtp = $request->file('img_KTP')->getClientOriginalName();
            $request->img_KTP->move(public_path('ktp'), $namaKtp);
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt('12345678')
        ]);

        UserDetail::create([
            'user_id' => $user->id,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'address' => $input['address'],
            'gender' => $input['gender'],
            'date_of_birth' => $input['date_of_birth'],
            'img_KTP' => $namaKtp,
        ]);

        return $this->sendResponse('Success', 'User has been stored!!');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $namaKtp = '';
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::find($id);
        $userDetail = UserDetail::where('user_id', $user->id)->first();

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->save();

        if ($request->hasFile('img_KTP')) {
            $request->validate([
                'img_KTP' => 'image|mimes:jpg,png,jpeg|max:2048',
            ]);

            if ($userDetail->img_KTP) {
                unlink(public_path('ktp') . '/' . $user->img_KTP);
            }

            $namaKtp = $request->file('img_KTP')->getClientOriginalName();
            $request->img_KTP->move(public_path('ktp'), $namaKtp);
        }

        $userDetail->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'img_KTP' => $namaKtp
        ]);

        return $this->sendResponse('Success', 'User has been updated!!');
    }

    public function destroy($id): JsonResponse
    {
        $user = UserDetail::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found!!', []);
        }

        unlink(public_path('ktp') . '/' . $user->img_KTP);

        User::where('id', $user->user_id)->delete();
        $user->delete();

        return $this->sendResponse([], 'User has been deleted!!');
    }
}
