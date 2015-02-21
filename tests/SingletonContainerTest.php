<?php
use TomVerran\Di\SingletonContainer;
use TomVerran\MockContainer;

/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 15:02
 */

class SingletonContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockContainer
     */
    private $mockContainer;

    /**
     * @var SingletonContainer
     */
    private $singletonContainer;

    public function setUp()
    {
        $this->mockContainer = new MockContainer( [stdClass::class => new stdClass] );
        $this->singletonContainer = new SingletonContainer( $this->mockContainer );
    }

    /**
     * If a class has not been declared as a Singleton
     * the singleton container should never claim to be able to resolve it
     */
    public function testNonSingletonsAreNotResolvable()
    {
        $this->assertFalse( $this->singletonContainer->has( stdClass::class ) );
        $this->singletonContainer->add( stdClass::class );
        $this->assertTrue( $this->singletonContainer->has( stdClass::class ) );
    }

    /**
     * When a SingletonContainer is used to resolve a class
     * it should then store the instance and not resolve it again
     */
    public function testInstancesGetStoredInContainer()
    {
        $stdClass = $this->mockContainer->get( stdClass::class );
        $stdClass->fromMockContainer = true; //label this stdClass instance

        $this->singletonContainer->add( stdClass::class );
        $stdClass = $this->singletonContainer->get( stdClass::class );
        $this->assertTrue( $stdClass->fromMockContainer, 'stdClass came from MockContainer' );

        $this->mockContainer->setMapping( stdClass::class, null );
        $resolvedAgain = $this->singletonContainer->get( stdClass::class );

        $this->assertInstanceOf( stdClass::class, $resolvedAgain, 'stdClass retrieved' );
        $this->assertTrue( $stdClass->fromMockContainer, 'Same stdClass instance' );
    }
} 