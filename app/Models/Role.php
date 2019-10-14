<?php

namespace App\Models;

use App\Events\StudentGivenPosition;
use App\Events\StudentRemovedFromPosition;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Role extends Model
{
    use CrudTrait, PivotEventTrait, RevisionableTrait, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

     protected $primaryKey = 'id';
     public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = [
        'group_id',
        'student_id',
        'position_id',
        'position_name',
        'committee_year'
    ];

    protected $casts = [
        'committee_year' => 'integer'
    ];

    // protected $hidden = [];
    // protected $dates = [];
    protected $dispatchesEvents = [
        'created' => StudentGivenPosition::class,
        'deleted' => StudentRemovedFromPosition::class,
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->hasMany('App\Models\Student', 'user_role')->withPivot(['position_name', 'committee_year']);
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
