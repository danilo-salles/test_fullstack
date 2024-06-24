<?php

namespace App\Helpers;

use DateTime;

class IdadeHelper
{
    public static function calcularIdade($dataNascimento)
    {
        $agora = new DateTime();
        $dataNascimento = new DateTime($dataNascimento);
        $idade = $agora->diff($dataNascimento)->y;
        return $idade;
    }
}