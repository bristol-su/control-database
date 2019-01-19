<?php

namespace App\Models;

use App\Events\GroupActivated;
use App\Events\GroupCreated;
use App\Events\GroupDeactivated;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Group extends Model
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

    protected $table = 'groups';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'unioncloud_id',
        'email'
    ];
    // protected $hidden = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dispatchesEvents = [
        'created' => GroupCreated::class,
        'deleted' => GroupDeactivated::class,
        'restored' => GroupActivated::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function tags()
    {
        return $this->belongsToMany('App\Models\GroupTag');
    }

    public function students()
    {
        return $this->belongsToMany('App\Models\Student');
    }

    public function accounts()
    {
        return $this->hasMany('App\Models\Account');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
