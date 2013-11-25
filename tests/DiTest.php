<?php
/**
 * DiTest.php
 * @author Tom
 * @since 15/11/13
 */
$s = DIRECTORY_SEPARATOR; //run from whichever working dir you want!
require(dirname(__FILE__)."{$s}..{$s}vendor{$s}autoload.php");
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
        $class = $this->injector->resolve('tomverran\di\Injector');
        $this->assertTrue($class instanceof Injector);
    }
}