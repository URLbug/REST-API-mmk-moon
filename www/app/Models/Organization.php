<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization';
    protected $primaryKey = 'organizationID';
    protected $fillable = [
        'title'
    ];

    public function phone(): HasMany
    {
        return $this->HasMany(Phone::class, 'phoneID', 'phoneID');
    }

    public function building(): BelongsTo
    {
        return $this->BelongsTo(Building::class, 'buildingID', 'buildingID');
    }

    public function activity(): BelongsToMany
    {
        return $this->BelongsToMany(
            Activity::class,
            'organization_activity',
            'organizationID',
            'activityID'
        );
    }
}
