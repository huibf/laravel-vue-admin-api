<?php

namespace App\Domain\Common\Enum;

use Exception;

abstract class BaseEnum
{
    public $name;
    public $value;
    public $statusText;

    /**
     * BaseEnum constructor.
     * @param $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
        $this->statusText = self::getStatusTextByValue($value);
    }

    /**
     * @param $value
     * @return string
     */
    public static function getStatusTextByValue($value)
    {
        try {
            $name = static::$statusMap[$value];
        } catch (Exception $e) {
            $name = '';
        }
        return $name;
    }

    /**
     * @param $value
     * @return BaseEnum|null
     * @throws \ReflectionException
     */
    public static function byValue($value)
    {
        $enumClass = new \ReflectionClass(get_called_class());
        $constants = collect($enumClass->getConstants())->flip();
        if ($constants->has($value))
            return new static($constants->all()[$value], $value);
        return null;
    }

    /**
     * @param $methodName
     * @param $argument
     * @return BaseEnum|null
     */
    public static function __callStatic($methodName, $argument)
    {
        try {
            $value = constant('static::' . $methodName);
        } catch (Exception $e) {
            return null;
        }
        return new static($methodName, $value);
    }
}
