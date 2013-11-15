<?php
/**
 * ParserTest.php
 * @author Tom
 * @since 11/11/13
 */
$s = DIRECTORY_SEPARATOR; //run from whichever working dir you want!
require(dirname(__FILE__)."{$s}..{$s}vendor{$s}autoload.php");
use tomverran\di\Injector;

/**
 * Yeah, I thought I should do some testing.
 * Class BootstrapBladeCompilerTest
 */
class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Injector An instance of the compiler.
     * It is stateless so can be safely reused between tests.
     */
    private static $injector = null;

    /**
     * Create an instance of our injector
     */
    public static function setUpBeforeClass()
    {
        self::$injector = new Injector();
    }

    /**
     * Test the basic ability to load a class
     */
    public function testLoading()
    {
        $class = self::$injector->resolve('tomverran\di\Injector');
        $this->assertTrue($class instanceof Injector);
    }

    /**
     * Test that we lazy load classes ok
     */
    public function testLazyLoading()
    {
        self::$injector->setExpensive('tomverran\di\Injector');
        $class = self::$injector->resolve('tomverran\di\Injector');
        $this->assertTrue($class instanceof tomverran\di\LazyLoader);

        //make sure our lazy loader class is transparent
        $andAgain = $class->resolve('tomverran\di\injector');
        $this->assertTrue($andAgain instanceof tomverran\di\Injector);
    }

}