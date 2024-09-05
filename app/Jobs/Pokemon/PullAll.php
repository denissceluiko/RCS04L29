<?php

namespace App\Jobs\Pokemon;

use App\Services\Pokemon\PokemonAPI;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class PullAll implements ShouldQueue
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
        $response = PokemonAPI::list();

        if ($response === false) {
            return;
        }

        $count = $response['count'];
        $batch = $this->createBatch($count);
        $id = Bus::batch($batch)->dispatch();
    }

    protected function createBatch(int $count): array
    {
        $batch = [];

        for ($i = 1; $i<=$count; $i++) {
            $batch[] = new PullOne($i);
        }

        return $batch;
    }
}
