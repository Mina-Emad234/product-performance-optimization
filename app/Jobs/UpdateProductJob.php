<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductJob implements ShouldQueue
{
    use Batchable,Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data as $item) {
            if ($item['updated_at']!=null) {
                continue;
            }
            $update_data[] = $item;
        }
        DB::table('products')->upsert($update_data,'provider_id',['updated_at' =>   Carbon::now() ]);

    }
}
