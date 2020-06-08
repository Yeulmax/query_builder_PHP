<?php

namespace App\Core;


class Model implements \JsonSerializable
{

    public function __toArray(): array
    {
        return get_object_vars($this);
    }

    // Il est possible ici de remplacer l'objet courant par $this si vous le souhaitez
    public function hydrate(array $row)
    {
        $className = get_class($this);
        $articleObj = new $className();
        foreach ($row as $key => $value) {

            $method = 'set'.$key;
            if (method_exists($articleObj, $method)) {
                $articleObj->$method($value);
            }
        }

        return $articleObj;
    }

    public function jsonSerialize() {

        return $this->__toArray();
    }

}