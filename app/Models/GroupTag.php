<?php

namespace App\Models;

use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class GroupTag extends Model
{
    use CrudTrait, SoftDeletes, RevisionableTrait, PivotEventTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'group_tags';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'description',
        'reference',
        'group_tag_category'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getCategoryName()
    {
        return ($this->category !== null?$this->category->name:'-');
    }

    public function getFullReference()
    {
        return ($this->category !== null?$this->category->reference:'').'.'.$this->reference;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo('App\Models\GroupTagCategory', 'group_tag_category');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group')->withPivot('data')->withTimestamps();
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
