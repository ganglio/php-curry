<?php

namespace ganglio;

class Curry
{

    const NOT_CALLABLE = 1;
    const TOO_MANY_ARGS = 2;

    private $f = null;
    private $refl = null;
    private $fargs = 0;
    private $left = [];
    private $right = [];

    public function __construct($f, $left = [], $right = [])
    {
        if (!is_callable($f)) {
            throw new \InvalidArgumentException("Argument needs to be callable", self::NOT_CALLABLE);
        }

        $this->f = $f;
        $this->left = $left;
        $this->right = $right;

        $this->refl = new \ReflectionFunction($this->f);
        $this->fargs = $this->refl->getParameters();
    }

    public function __invoke()
    {
        $args = array_merge($this->left, func_get_args(), $this->right);

        if (count($args) > count($this->fargs)) {
            throw new \InvalidArgumentException("Too many paramenters. Max ".count($fargs), self::TOO_MANY_ARGS);
        }

        if (count($args) == count($this->fargs)) {
            return $this->refl->invokeArgs($args);
        }

        return new Curry($this->f, array_merge($this->left, func_get_args()), $this->right);
    }

    public function right()
    {
        $args = array_merge($this->left, $this->right, func_get_args());

        if (count($args) > count($this->fargs)) {
            throw new \InvalidArgumentException("Too many paramenters. Max ".count($fargs), self::TOO_MANY_ARGS);
        }

        if (count($args) == count($this->fargs)) {
            return $this->refl->invokeArgs(array_merge($this->left, $this->right, func_get_args()));
        }

        return new Curry($this->f, $this->left, array_merge($this->right, func_get_args()));
    }
}
