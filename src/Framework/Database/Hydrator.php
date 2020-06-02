<?php
namespace Framework\Database;

/**
 * Transforme un tableau en objet en utilisant les setters
 */
class Hydrator
{

    /**
     * Transforme un tableau en objet en utilisant les setters
     * @param array $array
     * @param $object
     * @return mixed
     */
    public static function hydrate(array $array, $object)
    {
        if (is_string($object)) {
            $instance = new $object();
        } else {
            $instance = $object;
        }
        foreach ($array as $key => $value) {
            $method = self::getSetter($key);
            if (method_exists($instance, $method)) {
                $instance->$method($value);
            } else {
                $property = lcfirst(self::getProperty($key));
                $instance->$property = $value;
            }
        }
        return $instance;
    }

    private static function getSetter(string $fieldName): string
    {
          return 'set' . self::getProperty($fieldName);
    }

    private static function getProperty(string $fieldName): string
    {
        return join('', array_map('ucfirst', explode('_', $fieldName)));
    }
}
