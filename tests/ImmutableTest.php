<?php

require_once __DIR__ . '/../src/Immutable.php';

class Mutable
{
    public $foo;

    public $bar;

    public function getFoo()
    {
        return $this->foo;
    }
}

$mutable = new Mutable();
$mutable->foo = 1;

$immutable = Immutable::fromMutable($mutable);
$immutable->foo = 2;
$immutable->setFunction('getFoo', function () {
    return $this->foo;
});

var_dump($mutable->getFoo());
var_dump($immutable->getFoo());
var_dump($immutable->isInstanceOf(Mutable::class));
var_dump($immutable->getClass());
