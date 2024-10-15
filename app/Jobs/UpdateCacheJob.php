<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;


class UpdateCacheJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Redis::exists('products')) {
            Redis::del('products');
        }
        $all = DB::table('products')->get(['name', 'color', 'kilometers', 'price', 'provider_id','updated_at']);
        Redis::set('products', $all->toJson());
    }
}
