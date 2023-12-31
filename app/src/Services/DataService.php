<?php

namespace App\Services;

class DataService
{

    public function random(): array{
        return [
            'string'=>bin2hex(random_bytes(5))
        ];
    }

}