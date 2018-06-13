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
var_dump($mutable->getFoo());

$immutable = Immutable::fromMutable($mutable);
$immutable->foo = 2;
$immutable->setFunction('getFoo', function () {
    return $this->foo;
});

var_dump($mutable->getFoo());
var_dump($immutable->getFoo());
var_dump($immutable->isInstanceOf(Mutable::class));
var_dump($immutable->getClass());

$mutableFromImmutable = $immutable->toMutable(true);
unset($immutable);
var_dump($mutableFromImmutable->getFoo());
var_dump($mutable->getFoo());

//Output:
//int(1)
//int(1)
//int(2)
//bool(true)
//string(7) "Mutable"
//int(2)
//int(2)
