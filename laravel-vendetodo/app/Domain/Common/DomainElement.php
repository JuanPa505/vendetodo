<?php

namespace App\Domain\Common;

use ReflectionClass;
use ReflectionMethod;
use stdClass;

abstract class DomainElement
{
    public abstract static function from(array $values): self;

    /**
     * @param array $listValues
     * @return DomainElement[]
     */
    public abstract static function fromArray(array $listValues): array;

    /**
     * @template T
     * @param class-string<T> $className
     * @return object
     */
    protected static function make(string $className, array | stdClass $value): object
    {
        if($value instanceof stdClass)
        {
            $value = (array)$value;
        }
        $reflectionClass = new ReflectionClass($className);
        $constructor = new ReflectionMethod($reflectionClass->getName(), '__construct');

        $attributes = [];

        foreach ($constructor->getParameters() as $parameter) {

            // Reviso si values tiene un valor para el parametro actual
            if(!isset($value[$parameter->getName()])) {
                continue;
            }

            // Remuevo el signo de interrogacion de los parametros opcionales
            // ?App\Domain\Marca -> App\Domain\Marca
            // int -> int
            $typeParameter = ltrim($parameter->getType(), '?');

            // Si es primitivo
            if (self::isPrimitive($typeParameter)) {
                $attributes[$parameter->getName()] = $value[$parameter->getName()];
                continue;
            }

            // Si es un arreglo
            if ($typeParameter == 'array') {
                $arrayOfClasses = $value[$parameter->getName()];
                $arrayObjective = [];

                foreach ($arrayOfClasses as $classOfArray) {
                    $attributeClass = new ReflectionClass($classOfArray);
                    if ($attributeClass->isSubclassOf(DomainElement::class)) { 
                        $instance = $attributeClass->newInstanceWithoutConstructor();
                        $arrayObjective[] = $instance->from($value[$parameter->getName()]);
                    }
                }

                $attributes[$parameter->getName()] = $arrayObjective;

                continue;
            }

            // Si es una clase que hereda de DomainElement::class
            $attributeClass = new ReflectionClass($typeParameter);
            if ($attributeClass->isSubclassOf(DomainElement::class)) {
                $instance = $attributeClass->newInstanceWithoutConstructor();
                $attributes[$parameter->getName()] = $instance->from($value[$parameter->getName()]);
            }

        }

        \Log::info($attributes);

        return $reflectionClass->newInstanceArgs($attributes);
    }

    private static function isPrimitive(string $type) {
        return in_array($type, ['int', 'float', 'boolean', 'string', 'object']);
    }

}