<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;

    protected $table = 'building';
    protected $primaryKey = 'buildingID';
    protected $fillable = [
        'address',
        'latitude',
        'longitude',
    ];

    public function organization(): HasMany
    {
        return $this->HasMany(Organization::class, 'buildingID', 'buildingID');
    }
}
