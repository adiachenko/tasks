<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailConfirmation extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id', 'email',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * Check if record is expired
     *
     * @return bool
     */
    public function expired()
    {
        $expiresAt = Carbon::parse($this->updated_at)->addMinutes(
            config('auth.email_confirmations.expire')
        );

        return $expiresAt->isPast();
    }
}
