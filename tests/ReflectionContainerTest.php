<?php
use TomVerran\ContainerParameterResolver;
use tomverran\di\ReflectionContainer;
use TomVerran\MockContainer;
use TomVerran\ParameterResolver;

/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 15:49
 */

class ReflectionContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \tomverran\di\ReflectionContainer
     */
    private $reflectionContainer;

    /**
     * this is used by the parameter resolver
     * @var MockContainer
     */
    private $mockContainer;

    /**
     * The ParameterResolver used by the container
     * @var ParameterResolver
     */
    private $parameterResolver;

    /**
     * Create a ReflectionContainer
     */
    public function setUp()
    {
        $this->mockContainer = new MockContainer( [] );
        $this->parameterResolver = new ContainerParameterResolver( $this->mockContainer );
        $this->reflectionContainer = new ReflectionContainer( $this->parameterResolver );
    }

    /**
     * Make sure we can create classes
     * which have no constructor arguments
     */
    public function testCreationOfNoArgsClasses()
    {
        $this->assertInstanceOf( stdClass::class, $this->reflectionContainer->get( stdClass::class ) );
    }

    /**
     * Attempt to create a new ReflectionContainer
     */
    public function testCreationOfAClassWithAParameter()
    {
        $this->mockContainer->setMapping( ParameterResolver::class, $this->parameterResolver );
        $instance = $this->reflectionContainer->get( ReflectionContainer::class );
        $this->assertInstanceOf( ReflectionContainer::class, $instance );
    }
} 