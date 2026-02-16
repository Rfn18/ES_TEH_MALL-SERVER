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

       if (Jenis::where('nama_jenis', $request->nama_jenis)->exists()) {
            return new ApiResources(false, "Nama jenis tidak boleh sama", null);
        }
        
        $jenis = Jenis::create([
            "nama_jenis" => $request->nama_jenis  
        ]);


        return new ApiResources(true, "Successfully created jenis", $jenis);

    }

    public function show($id) {
        $jenis = Jenis::find($id);
        if (!$jenis) {return new ApiResources(true, "List kosong.", $jenis);}

        return new ApiResources(true, "List data menu bedasaran id.", $jenis);
    }

    public function update(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            "kd_jenis" => "nullable",
            "nama_jenis" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        
        $jenis = Jenis::find($id);

        if (!$jenis) {return new ApiResources(false, "Id tidak ditemukan.", null);}

        $jenis->update([
            "kd_jenis" => $jenis->kd_jenis,
            "nama_jenis" => $request->nama_jenis
        ]);

     return new ApiResources(true, "Successfully updated data.", $jenis);
    }

    public function destroy($id) {
        $jenis = Jenis::find($id);
        if (!$jenis) {return new ApiResources(false, "Id tidak ditemukan", null);}
        
        $jenis->delete();

        return new ApiResources(true, "Sunccessfully deleted data.", $jenis);
    }
}
