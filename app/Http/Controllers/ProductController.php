<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    public function index()
    {
        if (!Redis::exists('products')) {
            $products = DB::table('products')->get(['name', 'color', 'kilometers', 'price', 'provider_id','updated_at']);
            Redis::set('products', $products->toJson());
        }
        return Redis::get('products');
    }

    public function statistics()
    {
        $results = DB::select("
            SELECT
                MIN(`price`) AS `min_price`,
                MAX(`price`) AS `max_price`,
                MIN(`kilometers`) AS `min_kilometers`,
                MAX(`kilometers`) AS `max_kilometers`
            FROM `products`
        ");

        $colors = DB::select("SELECT DISTINCT(`color`) AS `colors` FROM `products`");

        return response()->json([
            'min_max_values' => $results,
            'colors' => $colors
        ])->header("Cache-Control", "max-age=86400, public");
    }

    public function show($id)
    {
        $product = DB::select('SELECT * FROM `products` WHERE `provider_id` = ?', [$id]);
;       return response()->json($product);
    }
}
