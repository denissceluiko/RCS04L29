<?php

namespace App\Console\Commands\Pokemon;

use App\Models\Pokemon;
use App\Services\Pokemon\PokemonAPI;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class Pull extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poke:pull {id}';

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

        if (is_numeric($id)) {
            $response = PokemonAPI::id($id);
        } else {
            $response = PokemonAPI::name($id);

        }

        if ($response === false) {
            $this->error('Request failed.');
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

        $pokemon = Pokemon::pokedex($response['id'])->first();

        $this->info("{$pokemon->name} added to database.");
    }
}
