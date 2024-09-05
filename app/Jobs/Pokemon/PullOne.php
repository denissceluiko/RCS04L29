<?php

namespace App\Jobs\Pokemon;

use App\Models\Pokemon;
use App\Services\Pokemon\PokemonAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PullOne implements ShouldQueue
{
    use Batchable, Queueable;

    public ?int $id = null;
    public ?string $name = null;

    /**
     * Create a new job instance.
     */
    public function __construct(int|string $id)
    {
        if (is_numeric($id)) {
            $this->id = $id;
        } else {
            $this->name = $id;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()->canceled()) {
            return;
        }

        if (!is_null($this->id)) {
            $response = PokemonAPI::id($this->id);
        } else {
            $response = PokemonAPI::name($this->name);
        }

        if ($response === false) {
            return;
        }

        Pokemon::upsert([
            'name' => $response['name'],
            'height' => $response['height'],
            'weight' => $response['weight'],
            'pokedex_id' => $response['id'],
        ], [
            'pokedex_id',
        ]);

        // Nosūtīt e-pastu
        // $pokemon = Pokemon::pokedex($response['id'])->first();
    }
}
