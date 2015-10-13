<?php

namespace CurryTests;

use \ganglio\Curry;

class CurryTest extends \PHPUnit_Framework_TestCase
{

    protected $func = null;

    protected function setUp()
    {
        $this->func = function ($a, $b, $c) {
            return $a + $b + $c;
        };
    }

    protected function tearDown()
    {
        $this->func = null;
    }

    /**
     * @expectedException    InvalidArgumentException
     * expectedExceptionCode 1
     */
    public function testConstructorArgumentNotCallable()
    {
        new Curry(33);
    }

    /**
     * @expectedException    InvalidArgumentException
     * expectedExceptionCode 2
     */
    public function testTooManyArguments()
    {
        $cu = new Curry($this->func);
        $cu(1, 2, 3, 4);
    }

    /**
     * @expectedException    InvalidArgumentException
     * expectedExceptionCode 2
     */
    public function testRightTooManyArguments()
    {
        $cu = new Curry(function($a,$b){
            return $a/$b;
        });

        $cu(2)->right(2,3);
    }

    public function testReturnsCallable()
    {
        $this->assertTrue(
            is_callable(new Curry($this->func))
        );
    }

    public function testExactArguments()
    {
        $cu = new Curry($this->func);

        $this->assertEquals(
            6,
            $cu(1, 2, 3)
        );
    }

    public function testTwoArguments()
    {
        $cu = new Curry($this->func);
        $twoArgs = $cu(1,2);

        $this->assertEquals(
            6,
            $twoArgs(3)
        );
    }

    public function testMultiCurry()
    {
        $cu = new Curry($this->func);
        $one = $cu(1);
        $two = $one(2);

        $this->assertEquals(
            6,
            $two(3)
        );
    }

    public function testRightCurry()
    {
        $cu = new Curry(function($a,$b){
            return $a/$b;
        });

        $inv = $cu(1);
        $id  = $cu->right(1);

        $this->assertEquals(
            3,
            $id(3)
        );

        $this->assertEquals(
            0.5,
            $inv(2)
        );
    }

    public function testMixedArgs()
    {
        $cu = new Curry(function($a,$b,$c,$d) {
            return ($a+1/$b)/($c+1/$d);
        });

        $fn = $cu(0,1)->right(2);

        $this->assertEquals(
            2/3,
            $fn(1)
        );
    }
}
