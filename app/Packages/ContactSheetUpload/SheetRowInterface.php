<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 07/02/19
 * Time: 17:04
 */

namespace App\Packages\ContactSheetUpload;


use App\Models\Group;
use App\Models\Position;
use App\Models\Student;

abstract class SheetRowInterface
{

    /**
     * @var Position
     */
    protected $position;

    /**
     * @var Student
     */
    protected $student;

    /**
     * @var Group
     */
    protected $group;

    protected $elements;

    public function __construct(Position $position, Student $student, Group $group)
    {
        $this->position = $position;
        $this->student = $student;
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

    abstract public static function getHeaders();


}