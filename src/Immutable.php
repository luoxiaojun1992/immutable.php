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
        if ($persistent) {
            foreach ($this->new_attributes as $new_attribute_name => $new_attribute_value) {
                $this->old_obj->$new_attribute_name = $new_attribute_value;
            }
        }
        return $this->old_obj;
    }

    public function __set($name, $value)
    {
        $this->new_attributes[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->new_attributes)) {
            return $this->new_attributes[$name];
        }

        return $this->old_obj->$name;
    }

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->new_functions)) {
            return $this->new_functions[$name]->call($this, $arguments);
        }

        return call_user_func_array([$this->old_obj, $name], $arguments);
    }

    public function isInstanceOf(string $class)
    {
        return $this->old_obj instanceof $class;
    }

    public function setFunction(string $func_name, \Closure $func)
    {
        $this->new_functions[$func_name] = $func;
    }

    public function getClass()
    {
        return get_class($this->old_obj);
    }
}
