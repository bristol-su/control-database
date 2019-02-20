<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 07/02/19
 * Time: 17:04
 */

namespace App\Packages\GroupSheetUpload;


use App\Models\Group;
use App\Models\Position;
use App\Models\Student;

abstract class BaseSheetRow
{

    /**
     * @var Group
     */
    protected $group;

    protected $elements;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    abstract public function generateData();

    public function __toString()
    {
        return implode(',', $this->elements);
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function __get($key) {
        if(array_key_exists($key, $this->elements))
        {
            return $this->elements[$key];
        }
        return;
    }

    abstract public static function getHeaders();


}