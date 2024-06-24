<?php

namespace App\Helpers;

class FormataDataHelper
{
    public static function ajustaData($data)
    {
        return date('d-m-Y', strtotime($data));
    }
}