<?php

namespace App\Services\Pokemon;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PokemonAPI
{
    public static string $server = 'https://pokeapi.co/api/v2/';

    public static function get($endpoint, $query = []): Response|false
    {
        $response = Http::get(
            static::$server.
            $endpoint.
            (empty($query) ? '' : '?').
            http_build_query($query)
        );

        if ($response->failed()) {
            return false;
        }

        return $response;
    }

    public static function id(int $id): array|false
    {
        $response = static::get("pokemon/$id");

        return $response ? $response->json() : $response;
    }

    public static function name(string $name): array|false
    {
        $response = static::get("pokemon/$name");

        return $response ? $response->json() : $response;
    }

    public static function list(): array|false
    {
        $response = static::get("pokemon", ['limit' => 1]);

        return $response ? $response->json() : $response;
    }
}
