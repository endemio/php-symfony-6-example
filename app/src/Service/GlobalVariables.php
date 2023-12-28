<?php

namespace App\Service;

class GlobalVariables
{

    public function random(): string
    {
        return bin2hex(random_bytes(5));
    }

}