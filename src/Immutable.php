<?php

class Immutable
{
    protected $mutable = null;

    protected $new_attributes = [];

    protected $new_functions = [];

    private function __construct($mutable)
    {
        $this->mutable = $mutable;
    }

    private function __clone()
    {
        //do noting
    }

    public static function fromMutable($mutable)
    {
        return new static($mutable);
    }

    public function toMutable(bool $persistent = false)
    {
        if ($persistent) {
            foreach ($this->new_attributes as $new_attribute_name => $new_attribute_value) {
                $this->mutable->$new_attribute_name = $new_attribute_value;
            }
        }
        return $this->mutable;
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

        return $this->mutable->$name;
    }

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->new_functions)) {
            return $this->new_functions[$name]->call($this, $arguments);
        }

        return call_user_func_array([$this->mutable, $name], $arguments);
    }

    public function isInstanceOf(string $class)
    {
        return $this->mutable instanceof $class;
    }

    public function setFunction(string $func_name, \Closure $func)
    {
        $this->new_functions[$func_name] = $func;
    }

    public function getClass()
    {
        return get_class($this->mutable);
    }
}
