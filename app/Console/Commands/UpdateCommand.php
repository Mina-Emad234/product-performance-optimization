<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Jobs\UpdateCacheJob;
use App\Jobs\UpdateProductJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;


class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // first solution
    public function handle()
    {
        //get all data
        $data = Http::get('http://127.0.0.1:8000/api/products')->json();
        //chunck data
        foreach (array_chunk($data, 1000) as $chunk) {
            $jobChain[] = new UpdateProductJob($chunk);
        }

        Bus::chain(
            Bus::batch($jobChain),
            new UpdateCacheJob()
        )->dispatch();

    }

    // second solution
    // public function handle()
    // {
    //     //get all data
    //     $data = Http::get('http://127.0.0.1:8000/api/products')->json();
    //     //chunck data
    //     foreach (array_chunk($data, 1000) as $chunk) {
    //         DB::table('products')->upsert($chunk,'provider_id',['updated_at' =>   Carbon::now() ]);
    //     }
    //     //check and update cache
    //     if (Redis::exists('products')) {
    //         Redis::del('products');
    //     }
    //     $all = DB::table('products')->get(['name', 'color', 'kilometers', 'price', 'provider_id','updated_at']);
    //     Redis::set('products', $all->toJson());
    // }
}
