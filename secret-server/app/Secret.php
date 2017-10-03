<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    public $xmlName = 'Secret';
    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = 'hash';
    protected $fillable = ['secretText'];
    protected $dates = ['createdAt', 'expiresAt'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            do
            {
                $model->{$model->getKeyName()} = hash('sha256', openssl_random_pseudo_bytes(32));
            }
            while(Secret::where('hash', '=', $model->{$model->getKeyName()})->exists());

            $model->createdAt = $model->freshTimestamp();
        });
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format("Y-m-d\TH:i:s.v\Z");
    }

    /**
     * Scope a query to only show valid secret.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('remainingViews', '>', '0')
                     ->where(function($q) {
                                 $q->where('expiresAt', '>', Carbon::now())
                                   ->orWhereRaw('expiresAt = createdAt');
                             });
    }
}
