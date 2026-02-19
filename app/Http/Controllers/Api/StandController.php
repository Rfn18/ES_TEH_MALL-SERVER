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
            return new ApiResources(false, "Nama stand tidak boleh sama", null);
        }
        
        $stand = Stand::create([
            "nama_stand" => $request->nama_stand  
        ]);


        return new ApiResources(true, "Successfully created jenis", $stand);

    }

    public function show($id) {
        $stand = Stand::find($id);

        return new ApiResources(true, "List data menu bedasaran id.", $stand);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            "kd_stand" => "nullable",
            "nama_stand" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stand = Stand::findOrFail($id);
        
        $stand->update([
            "nama_stand" => $request->nama_stand,
        ]);

         return new ApiResources(true, "Successfully updated data.", $stand);

    }

    public function destroy($id) {
        $stand = Stand::find($id);
        $stand->delete();
        if (!$stand) {return new ApiResources(false, "Id tidak ditemukan", null);}

        return new ApiResources(true, "Sunccessfully deleted data.", $stand);
    }
}
