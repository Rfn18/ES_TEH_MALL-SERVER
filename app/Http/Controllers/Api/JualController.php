<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use App\Models\Jual;
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
            "no_transaksi" => "nullable",
            "total_biaya_produksi" => "required|number",
            "total_omzet" => "required|number",
            "selisih" => "required|number",
            "tanggal" => "required|date",
            "stand_id" => "require|exist, no_transaksi"
        ]);
    }
}
