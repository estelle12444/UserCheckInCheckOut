<?php

namespace App;

class Helper
{
    public static function searchByNameAndId(string $name, int $id)
    {
        $data = config($name);
        $key = array_search($id, array_column($data, 'id'));
        return (Object) $data[$key];
    }
}
