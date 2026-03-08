<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\DetailJual;
use App\Models\Jual;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JualController extends Controller
{
    public function index() {
        $jual = Jual::with("menu")->paginate(10);
        if ($jual->count() === 0) {return new ApiResources(true, "belum ada transaksi.", null);}

        return new ApiResources(true, "List data transaksi.", $jual);
    }

    public function userId() {
        $jual = Jual::with("menu")->where('user_id', auth()->id())->paginate(10);
        if ($jual->count() === 0) {return new ApiResources(true, "belum ada transaksi.", null);}

        return new ApiResources(true, "List data transaksi.", $jual);
    }

    public function storeJual(Request $request) {
        $validator = Validator::make($request->all(), [
            'jual_id' => 'nullable|exists:juals,no_transaksi',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (Jual::where('stand_id', auth()->user()->stand_id)->where('tanggal', $request->tanggal)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi pada tanggal tersebut sudah ada.',
                'data' => null
            ], 422);
        }

        $tanggal = Carbon::parse($request->tanggal)->startOfDay();

        $jual = Jual::firstOrCreate(
            [
                'stand_id' => auth()->user()->stand_id,
                'tanggal' => $tanggal,
            ],
            [
                'user_id' => auth()->id(),
                'total_biaya_produksi' => 0,
                'total_omzet' => 0,
                'selisih' => 0,
            ]
        );

        return new ApiResources(true, "Successfully created transactions.", $jual);
    }

    public function storeDetailJual(Request $request) {
        $validator = Validator::make($request->all(), [
            '*.jual_id' => 'required|exists:juals,no_transaksi',
            '*.menu_id' => 'required|exists:menus,kd_menu',
            '*.jumlah' => 'required|integer|min:1',
            '*.sisa' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

        $data = $request->all();
        $jual = Jual::where('no_transaksi', $data[0]['jual_id'])->first();

         if (!$jual) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.'
                ], 404);
            }

        foreach ($data as $item) {
            $menu = Menu::where('kd_menu', $item['menu_id'])->first();
        
            if(!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu tidak ditemukan.'
                ], 404);
            }

            $laku = $item['jumlah'] - $item['sisa'];
            $omzet = $laku * $menu->harga_satuan;
            $biaya_produksi = $laku * $menu->biaya_produksi;

            if ($item['jumlah']< $item['sisa']) {
                return response()->json([
                    'success' => false,
                    'errors' => 'jumlah barang tidak cukup!'
                ], 422);
        }

            $existingDetail = DetailJual::where('jual_id', $item['jual_id'])
                ->where('menu_id', $item['menu_id'])
                ->first();

            if ($existingDetail) {
                return response()->json([
                    'success' => false,
                    'errors' => 'barang sudah tercatat!'
                ], 422);
            }

            DetailJual::create([
                "jual_id" => $item['jual_id'],
                "menu_id" => $item['menu_id'],
                "jumlah" => $item['jumlah'],
                "sisa" => $item['sisa'],
                "laku" => $laku,
                "harga_satuan" => $menu->harga_satuan,
            ]);

            $jual->increment('total_biaya_produksi', $biaya_produksi);
            $jual->increment('total_omzet', $omzet);
            }

            $jual->update([
                'selisih' => $jual->total_omzet - $jual->total_biaya_produksi,
            ]);
            DB::commit();

            return new ApiResources(true, "Successfully added transaction details.", $jual);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => 'false',
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function show($id) {
        $jual = Jual::with("menu")->findOrFail($id);
        
        return new ApiResources(true, 'List data bedasarkan id.', $jual);
    }
}
