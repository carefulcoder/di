<?php
use Interop\Container\Exception\NotFoundException;
use TomVerran\Di\InterfaceContainer;
use TomVerran\MockContainer;

/**
 * Created by PhpStorm.
 * User: tom
 * Date: 21/02/15
 * Time: 13:54
 */

class InterfaceContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InterfaceContainer
     */
    private $interfaceContainer;

    public function setUp()
    {
        $stdClass = new stdClass;
        $stdClass->fromMockContainer = true;
        $mc = new MockContainer([stdClass::class => $stdClass] );
        $this->interfaceContainer = new InterfaceContainer( $mc );
    }

    public function testMappingIdsToOtherIds()
    {
        $this->interfaceContainer->add( 'cats', stdClass::class );
        $this->assertTrue( $this->interfaceContainer->has( 'cats' ) );

        $stdClass = $this->interfaceContainer->get( 'cats' );
        $this->assertTrue( $stdClass->fromMockContainer );
    }

    public function testExceptionThrownWhenNoMapping()
    {
        $this->assertFalse( $this->interfaceContainer->has( 'dogs' ) );
        $this->setExpectedException( NotFoundException::class );
        $this->interfaceContainer->get( 'dogs' );
    }


} 