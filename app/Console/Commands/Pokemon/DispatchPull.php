<?php

namespace App\Console\Commands\Pokemon;

use App\Jobs\Pokemon\PullOne;
use App\Models\Pokemon;
use App\Services\Pokemon\PokemonAPI;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Bus;

class Pull extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poke:pull-queue {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        Bus::batch([
            new PullOne($id),
        ])->dispatch();

        $this->info("Pulling of $id added to queue.");
    }
}
