<?php

namespace App\Traits;
use Illuminate\Support\Collection;

trait JSONCamelize
{
    public function camelize(string $input, string $separator = '_')
    {
        return str_replace($separator, '', lcfirst(ucwords($input, $separator)));
    }
    
    public function toCamelCase(object | array $obj): object | array
    {
        $updatedObj = (object) [];

        if ($obj instanceof Collection) {
            $obj = json_decode($obj->toJson(), true);
        }

        if (is_array($obj)) {
            $updatedObj = [];
        }
        
        foreach ($obj as $key => $value) {
            $updatedKey = $this->camelize($key);
            
            if (is_object($value) || is_array($value)) {
                $value = $this->toCamelCase($value);
            }

            if (is_array($updatedObj)) {
                $updatedObj[$updatedKey] = $value;
                continue;
            }
            $updatedObj->$updatedKey = $value;
        }
        
        return $updatedObj;
    }
}