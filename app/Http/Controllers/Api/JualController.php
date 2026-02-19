<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\DetailJual;
use App\Models\Jual;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JualController extends Controller
{
    public function index() {
        $jual = Jual::paginate(10);
        if ($jual->count() === 0) {return new ApiResources(true, "belum ada transaksi.", null);}

        return new ApiResources(true, "List data transaksi.", $jual);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'stand_id' => 'required|exists:stands,kd_stand',
            "sisa" => 'required|integer',
            'tanggal' => 'required|date',

            'menus' => 'required|array|min:1',
            'menus.*.menu_id' => 'required|exists:menus,kd_menu',
            'menus.*.jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        
        $jual = Jual::create([
            'stand_id' => $request->stand_id,
            'tanggal' => $request->tanggal,
            'total_biaya_produksi' => 0,
            'total_omzet' => 0,
            'selisih' => 0,
        ]);

        $totalBiayaProduksi = 0;
        $totalOmzet = 0;

        foreach ($request->menus as $item) {

            $menu = Menu::findOrFail($item['menu_id']);

            $jumlah = $item['jumlah'];
            $laku = $item['jumlah'] - $request->sisa;

            $subtotalBPP = $menu->biaya_produksi * $laku;
            $omzet = $menu->harga_satuan * $laku;

            DetailJual::create([
                'jual_id' => $jual->no_transaksi,
                'menu_id' => $menu->kd_menu,
                'jumlah' => $jumlah,
                'sisa' => $request->sisa,
                'laku' => $laku,
                'harga_satuan' => $menu->harga_satuan,
                'subtotal_biaya_produksi' => $subtotalBPP,
                'omzet' => $omzet,
            ]);

            $totalBiayaProduksi += $subtotalBPP;
            $totalOmzet += $omzet;
        }

         $jual->update([
            'total_biaya_produksi' => $totalBiayaProduksi,
            'total_omzet' => $totalOmzet,
            'selisih' => $totalOmzet - $totalBiayaProduksi,
        ]);

        return new ApiResources(true, "Successfully created transactions.", $jual);
    }

    public function show($id) {
        $jual = Jual::with("menu")->findOrFail($id);

        return new ApiResources(true, 'List data bedasarkan id.', $jual);
    }
}
