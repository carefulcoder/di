<?php
use TomVerran\Di\AggregateContainer;
use TomVerran\Di\Registry\ProviderRegistry;
use TomVerran\Di\Registry\SingletonRegistry;
use TomVerran\Di\SingletonContainer;
use TomVerran\ParameterResolver;

/**
 * AggregateContainer exists to register all the different types of container as singletons
 * and also use each type of container in turn to actually look up the dependencies
 * Class AggregateContainerTest
 */
class AggregateContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AggregateContainer
     */
    private $container;

    /**
     * @var SingletonContainer
     */
    private $singletonContainer;

    public function setUp()
    {
        $this->container = new AggregateContainer;
        $this->singletonContainer = $this->container->get( SingletonRegistry::class );
    }

    /**
     * make sure we can resolve the singleton container
     * @throws \TomVerran\Di\Exception\NotFoundException
     */
    public function testSingletonContainerIsResolvable()
    {
        $this->assertInstanceOf( SingletonContainer::class, $this->singletonContainer );
    }

    /**
     * The aggregate container should've registered the singleton container as a singleton
     */
    public function testSingletonContainerHasItself()
    {
        $this->assertTrue( $this->singletonContainer->has( SingletonRegistry::class ) );
    }

    /**
     * The aggregate container should've registered the provider container as a singleton
     */
    public function testSingletonContainerHasProviderContainer()
    {
        $this->assertTrue( $this->singletonContainer->has( ProviderRegistry::class ) );
    }

    /**
     * The aggregate container should've registered the parameter resolver as a singleton
     */
    public function testSingletonContainerHasParameterResolver()
    {
        $this->assertTrue( $this->singletonContainer->has( ParameterResolver::class ) );
    }
} 