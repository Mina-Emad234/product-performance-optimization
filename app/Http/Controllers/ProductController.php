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
        $data = DB::select("
            SELECT
                MIN(`price`) AS `min_price`,
                MAX(`price`) AS `max_price`,
                MIN(`kilometers`) AS `min_kilometers`,
                MAX(`kilometers`) AS `max_kilometers`,
                GROUP_CONCAT(DISTINCT color) AS colors
            FROM `products`
        ");

        return response()->json([
            'min_price' => $data[0]->min_price,
            'max_price' => $data[0]->max_price,
            'min_kilometers' => $data[0]->min_kilometers,
            'max_kilometers' => $data[0]->max_kilometers,
            'colors' => explode(',', $data[0]->colors),
        ])->header("Cache-Control", "max-age=86400, public");
    }

    public function show($id)
    {
        if (!Redis::exists('product:'.$id)) {
            $product = DB::select('SELECT * FROM `products` WHERE `provider_id` = ?', [$id]);
            Redis::setex('product:'.$id, 3600, json_encode($product));
        }

        return json_decode(Redis::get('product:'.$id));

    }
}
