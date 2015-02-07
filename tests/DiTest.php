<?php
/**
 * DiTest.php
 * @author Tom
 * @since 15/11/13
 */
use tomverran\di\Injector;

/**
 * Yeah, I thought I should do some testing.
 */
class DiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Injector An instance of the injector
     */
    private $injector = null;

    /**
     * Create an instance of our injector
     */
    public function setUp()
    {
        $this->injector = new Injector();
    }

    /**
     * Test the basic ability to load a class
     */
    public function testLoading()
    {
        $obj = $this->injector->get('tomverran\di\Injector');
        $this->assertTrue($obj instanceof Injector);
    }

    /**
     * Test the use of a callable class provider
     * to handle class instantiation
     */
    public function testProvider()
    {
        $called = false;
        $this->injector->bind(function($class) use(&$called) {
            $called = true;
            return new $class();
        }, 'tomverran\di\Injector');


        $obj = $this->injector->resolve('tomverran\di\Injector');
        $this->assertTrue($obj instanceof Injector);
        $this->assertTrue($called);
    }
}