<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';
    protected $primaryKey = 'activityID';
    protected $fillable = [
        'title',
    ];

    public function organization(): BelongsToMany
    {
        return $this->belongsToMany(
            Organization::class,
            'organization_activity',
            'activityID',
            'organizationID'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parentActivityID');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parentActivityID');
    }
}
