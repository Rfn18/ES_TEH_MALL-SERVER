<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisController extends Controller
{
      public function index() {
        $jenis = Jenis::paginate(10);
        if ($jenis->count() === 0) { 
            return new ApiResources(true, "List masih kosong", $jenis);
        }

        return new ApiResources(true, "List data jenis", $jenis);
    }
    
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            "kd_jenis" => "nullable",
            "nama_jenis" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (is_numeric($request->nama_jenis)) {
           return response()->json([
                'success' => false,
                'message' => 'Nama jenis tidak boleh angka.',
                'data' => null
            ], 422);
        }

        if (Jenis::where('nama_jenis', $request->nama_jenis)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama jenis tidak boleh sama.',
                'data' => null
            ], 422);
        }
        
        $jenis = Jenis::create([
            "nama_jenis" => $request->nama_jenis  
        ]);

        return new ApiResources(true, "Successfully created jenis", $jenis);

    }

    public function show($id) {
        $jenis = Jenis::findOrFail($id);
        return new ApiResources(true, "List data menu bedasaran id.", $jenis);
    }

    public function update(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            "kd_jenis" => "nullable",
            "nama_jenis" => "sometimes|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if (Jenis::where('nama_jenis', $request->nama_jenis)->where('kd_jenis', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama jenis tidak boleh sama.',
                'data' => null
            ], 422);
        }

        if (is_numeric($request->nama_jenis)) {
            return response()->json([
                'success' => false,
                'message' => 'Nama jenis tidak boleh angka.',
                'data' => null
            ], 422);
        }
        
        $jenis = Jenis::findOrFail($id);
    
        $jenis->update([
            "kd_jenis" => $jenis->kd_jenis,
            "nama_jenis" => $request->nama_jenis
        ]);

     return new ApiResources(true, "Successfully updated data.", $jenis);
    }

    public function destroy($id) {
        $jenis = Jenis::findOrFail($id);
        
        $jenis->delete();

        return new ApiResources(true, "Successfully deleted data.", $jenis);
    }
}
