<?php

class Test
{
    public $toAlter = 0;

    public function foo()
    {
        $this->toAlter = 1;
        return $this;
    }

    public function foo2()
    {
        echo $this->toAlter;
    }
}

$t = new Test();

$t->foo()->foo2();