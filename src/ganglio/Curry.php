<?php

namespace ganglio;

class Curry
{

    const NOT_CALLABLE = 1;
    const TOO_MANY_ARGS = 2;

    private $f = null;
    private $args = [];

    public function __construct($f, $args = [])
    {
        if (!is_callable($f)) {
            throw new \InvalidArgumentException("Argument needs to be callable", self::NOT_CALLABLE);
        }
        $this->f = $f;
        $this->args = $args;
    }

    public function __invoke()
    {
        $args = array_merge($this->args, func_get_args());

        $refl = new \ReflectionFunction($this->f);
        $fargs = $refl->getParameters();

        if (count($args) > count($fargs)) {
            throw new \InvalidArgumentException("Too many paramenters. Max ".count($fargs), self::TOO_MANY_ARGS);
        }

        if (count($args) == count($fargs)) {
            return $refl->invokeArgs($args);
        }

        return new Curry($this->f, $args);
    }
}
