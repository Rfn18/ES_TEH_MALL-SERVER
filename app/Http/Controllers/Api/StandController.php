<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StandController extends Controller
{
      public function index() {
        $stand = Stand::paginate(10);
        if ($stand->count() === 0) { 
            return new ApiResources(true, "List masih kosong", $stand);
        }

        return new ApiResources(true, "List data stand", $stand);
    }
    
    public function store(Request $request) {
       $validator = Validator::make($request->all(), [
            "kd_stand" => "nullable",
            "nama_stand" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

       if (Stand::where('nama_stand', $request->nama_stand)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama stand tidak boleh sama.',
                'data' => null
            ], 422);
        }
        
        $stand = Stand::create([
            "nama_stand" => $request->nama_stand  
        ]);


        return new ApiResources(true, "Successfully created stand", $stand);

    }

    public function show($id) {
        $stand = Stand::findOrFail($id);

        return new ApiResources(true, "List data stand bedasarkan id.", $stand);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            "kd_stand" => "nullable",
            "nama_stand" => "sometimes|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stand = Stand::findOrFail($id);
        if (Stand::where('nama_stand', $request->nama_stand)->where('kd_stand', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama stand tidak boleh sama.',
                'data' => null
            ], 422);
        }        

        $stand->update([
            "nama_stand" => $request->nama_stand,
        ]);

         return new ApiResources(true, "Successfully updated data.", $stand);

    }

    public function destroy($id) {
        $stand = Stand::findOrFail($id);
        $stand->delete();

        return new ApiResources(true, "Successfully deleted data.", $stand);
    }
}
