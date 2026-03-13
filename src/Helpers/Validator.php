<?php
namespace Pendasi\Rest\Helpers;

class Validator{
    public static function required($value){
        return !empty($value);
    }
}