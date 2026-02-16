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
        $menu = Menu::paginate(10);

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

        $jenis = Jenis::findOrFail($request->jenis_id);

        $menu = $jenis->menu()->create([
            "nama_menu" => $request->nama_menu,
            "harga_satuan" => $request->harga_satuan,   
            "biaya_produksi" => $request->biaya_produksi,   
            "jenis_id" => $request->jenis_id
        ]);

        return new ApiResources(true, "Successfully created menu", $menu);

    }

    public function show($id) {
        $menu = Menu::find($id);

        return new ApiResources(true, "List data menu bedasaran id.", $menu);
    }

    public function update(Request $request, $id) {
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

        $menu = Menu::findOrFail($id);
            $menu->update([
                "nama_menu" => $request->nama_menu,
                "harga_satuan" => $request->harga_satuan,   
                "biaya_produksi" => $request->biaya_produksi,   
                "jenis_id" => $request->jenis_id
            ]);

         return new ApiResources(true, "Successfully updated data.", $menu);

    }

    public function destroy($id) {
        $menu = Menu::find($id);
        $menu->delete();

         return new ApiResources(true, "Sunccessfully deleted data.", $menu);
    }
    
}
