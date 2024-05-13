<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'uuid'];
    protected $hidden = ['id'];
    protected $fillable = [
        'uuid',
        'country_id',
        'name',
        'email',
        'whatsapp_number',
        'surfing_experience',
        'visit_date',
        'desired_board',
        'file_id_verification'
    ];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

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
            $request->has('email') && !is_null($request->input('email')),
            function ($query) use ($request) {
                $query->where('email', 'like', '%'. $request->email .'%');
            }
        )->when(
            $request->has('number') && !is_null($request->input('number')),
            function ($query) use ($request) {
                $query->where('whatsapp_number', 'like', '%'. $request->number .'%');
            }
        )->when(
            $request->has('desired_board') && !is_null($request->input('desired_board')),
            function ($query) use ($request) {
                $query->where('desired_board', 'like', '%'. $request->desired_board .'%');
            }
        )->when(
            $request->has('visit_date') && !is_null($request->input('visit_date')),
            function ($query) use ($request) {
                $query->whereDate('visit_date', Carbon::parse($request->visit_date)->format('Y-m-d'));
            }
        )->when(
            $request->has('country') && !is_null($request->input('country')),
            function ($query) use ($request) {
                $query->whereHas('country', function ($subquery) use ($request) {
                    return $subquery->where('name', 'like', '%' . $request->country . '%')
                        ->orWhere('code', 'like', '%' . $request->country . '%');
                });
            }
        );
    }
}
