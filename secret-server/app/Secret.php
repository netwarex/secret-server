<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
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
                $model->{$model->getKeyName()} = hash('sha256', microtime().rand(1000,9999));
            }
            while(Secret::where('hash', '=', $model->{$model->getKeyName()})->exists());

            $model->createdAt = $model->freshTimestamp();
        });
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format("Y-m-d\TH:i:s.v\Z");
    }
}
