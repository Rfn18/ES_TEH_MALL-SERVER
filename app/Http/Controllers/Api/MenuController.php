<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\Jenis;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index() {
        $menu = Menu::with('jenis')->paginate(10);
        if ($menu->count() === 0) { 
            return new ApiResources(true, "List masih kosong", $menu);
        }

        return new ApiResources(true, "List data menu", $menu);
    }
    
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            "kd_menu" => "nullable",
            "nama_menu" => "required|string",
            "harga_satuan" => "required|numeric",
            "biaya_produksi" => "required|numeric",
            "jenis_id" => "required|exists:jenis,kd_jenis"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jenis = Jenis::where('kd_jenis', $request->jenis_id)->firstOrFail();

        if (Menu::where('nama_menu', $request->nama_menu)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama menu tidak boleh sama.',
                'data' => null
            ], 422);
        }

        $menu = $jenis->menu()->create([    
            "nama_menu" => $request->nama_menu,
            "harga_satuan" => $request->harga_satuan,   
            "biaya_produksi" => $request->biaya_produksi,   
            "jenis_id" => $request->jenis_id
        ]);

        return new ApiResources(true, "Successfully created menu", $menu);

    }

    public function show($id) {
        $menu = Menu::with('jenis')->findOrFail($id);
    
        return new ApiResources(true, "List data menu bedasarkan id.", $menu);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            "kd_menu" => "nullable",
            "nama_menu" => "sometimes|string",
            "harga_satuan" => "sometimes|numeric",
            "biaya_produksi" => "sometimes|numeric",
            "jenis_id" => "sometimes|exists:jenis,kd_jenis"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu = Menu::findOrFail($id);
        if (Menu::where('nama_menu', $request->nama_menu)->where('kd_menu', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama menu tidak boleh sama.',
                'data' => null
            ], 422);
        }
        
        $menu->update([
            "nama_menu" => $request->nama_menu,
            "harga_satuan" => $request->harga_satuan,   
            "biaya_produksi" => $request->biaya_produksi,   
            "jenis_id" => $request->jenis_id,
        ]);

         return new ApiResources(true, "Successfully updated data.", $menu);

    }

    public function destroy($id) {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return new ApiResources(true, "Successfully deleted data.", $menu);    
    }
    
}
