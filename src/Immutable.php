<?php

class Immutable
{
    protected $old_obj = null;

    protected $new_attributes = [];

    protected $new_functions = [];

    private function __construct($mutable)
    {
        $this->old_obj = $mutable;
    }

    private function __clone()
    {
        //
    }

    public static function fromMutable($mutable)
    {
        return new static($mutable);
    }

    public function toMutable($persistent = false)
    {
        $old_object = $this->getOldObject();
        if ($persistent) {
            foreach ($this->getNewAttributes() as $new_attribute_name => $new_attribute_value) {
                $old_object->$new_attribute_name = $new_attribute_value;
            }
        }
        return $old_object;
    }

    public function __set($name, $value)
    {
        $this->new_attributes[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->getNewAttributes())) {
            return $this->getNewAttributes()[$name];
        }

        return $this->getOldObject()->$name;
    }

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->new_functions)) {
            return $this->new_functions[$name]->call($this, $arguments);
        }

        return call_user_func_array([$this->getOldObject(), $name], $arguments);
    }

    public function isInstanceOf(string $class)
    {
        return $this->getOldObject() instanceof $class;
    }

    public function setFunction(string $func_name, \Closure $func)
    {
        $this->new_functions[$func_name] = $func;
    }

    public function getClass()
    {
        return get_class($this->getOldObject());
    }

    public function getOldObject()
    {
        return $this->old_obj;
    }

    public function getNewAttributes()
    {
        return $this->new_attributes;
    }
}
