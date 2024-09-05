<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pokemon extends Model
{
    use HasFactory;

    protected $fillable = ['pokedex_id', 'name', 'height', 'weight'];

    public function scopePokedex(Builder $query, int $id): void
    {
        $query->where('pokedex_id', $id);
    }
}
