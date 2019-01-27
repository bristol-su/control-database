<?php

if(!function_exists('keyprefix'))
{

    function keyprefix($keyprefix, Array $array) {

        foreach($array as $k=>$v){
            $array[$keyprefix.$k] = $v;
            unset($array[$k]);
        }

        return $array;
    }

}