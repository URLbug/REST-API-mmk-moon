<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phone extends Model
{
    use HasFactory;

    protected $table = 'phone';
    protected $primaryKey = 'phoneID';

    protected $fillable = [
        'phoneNumber',
    ];

    public function organization(): BelongsTo
    {
        return $this->BelongsTo(Organization::class, 'phoneID', 'phoneID');
    }
}
