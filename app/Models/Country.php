<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'uuid'];
    protected $appends = ['flag'];
    protected $hidden = ['id'];
    protected $fillable = [
        'uuid',
        'name',
        'code'
    ];

    /**
     * Scope for filter when user sent the parameter on request
     *
     * @param $model
     * @param $request
     */
    public function scopeFilterSearch($model, $request)
    {
        return $model->when(
            $request->has('name') && !is_null($request->input('name')),
            function ($query) use ($request) {
                $query->where('name', 'like', '%'. $request->name .'%');
            }
        )->when(
            $request->has('code') && !is_null($request->input('code')),
            function ($query) use ($request) {
                $query->where('code', 'like', '%'. $request->code .'%');
            }
        );
    }

    /**
     * Get url flag from country code
     *
     * @return string
     */
    public function getFlagAttribute(): string
    {
        return country_flag($this->code);
    }
}
