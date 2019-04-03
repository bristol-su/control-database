<?php

namespace App\Models;

use App\Events\StudentGivenPosition;
use App\Events\StudentRemovedFromPosition;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class PositionStudentGroup extends Model
{
    use CrudTrait, PivotEventTrait, RevisionableTrait, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'position_student_group';
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
        return $this->belongsTo('App\Models\Student');
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
