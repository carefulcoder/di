<?php
/**
 * DiTest.php
 * @author Tom
 * @since 15/11/13
 */
use tomverran\di\Container;
use tomverran\di\Provider\CallableProvider;

/**
 * Yeah, I thought I should do some testing.
 */
class DiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container An instance of the injector
     */
    private $injector = null;

    /**
     * Create an instance of our injector
     */
    public function setUp()
    {
        $this->injector = new Container;
    }

    /**
     * Test the basic ability to load a class
     */
    public function testLoading()
    {
        $obj = $this->injector->get( Container::class );
        $this->assertTrue($obj instanceof Container);
    }

    /**
     * Test the use of a callable class provider
     * to handle class instantiation
     */
    public function testProvider()
    {
        $called = false;
        $provider = new CallableProvider( function() use (&$called) {
            $called = true; //log this for testing
            return new Container;
        } );

        $this->injector->bindProvider( Container::class, $provider );
        $obj = $this->injector->get( Container::class );
        $this->assertTrue( $obj instanceof Container );
        $this->assertTrue( $called );
    }
}