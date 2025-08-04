<?php

namespace App\Repository;

interface Repository
{
    public static function getAll();
    public static function getById(int $id);
}
