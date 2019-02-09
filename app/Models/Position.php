<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Position extends Model
{
    use CrudTrait, SoftDeletes, RevisionableTrait, PivotEventTrait;

    public static function boot(){
        parent::boot();
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'positions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];

    // protected $hidden = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
    ];

    public function positionStudentGroups()
    {
        return $this->hasMany('App\Models\PositionStudentGroup');
    }

}
