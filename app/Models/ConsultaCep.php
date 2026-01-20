<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultaCep extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'consulta_ceps';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'cep',
        'payload',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the user that owns the ConsultaCep.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
