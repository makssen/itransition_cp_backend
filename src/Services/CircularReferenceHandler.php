<?php

namespace App\Services;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId();
    }
}
