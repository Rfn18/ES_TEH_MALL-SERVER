<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserManageController extends Controller
{
    public function index() {
        $user = User::paginate(10);
        if ($user->count() === 0) {
            return new ApiResources(true, "User list is empty.", null);
        };

        return new ApiResources(true, "List data users.", $user);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'password' => "required|string",
            'role' => "nullable|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        return new ApiResources(true, "Successfully created users.", $user);
    }

    public function show($id) {
        $user = User::find($id);

        if (!$user) {
            return new ApiResources(false, "User not found.", null);
        }

        return new ApiResources(true, "List data user based on id.", $user);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'password' => "required|string",
            'role' => "nullable|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if (!$user) {
            return new ApiResources(false, "User not found.", null);
        }

        $user->update([
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        return new ApiResources(true, "Successfully updated user.", $user);
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
            return new ApiResources(false, "User not found.", null);
        }

        $user->delete();

        return new ApiResources(true, "Successfully deleted user.", null);
    }

}
