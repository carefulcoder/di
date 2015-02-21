<?php
use Interop\Container\ContainerInterface;
use TomVerran\Di\AggregateContainer;
use TomVerran\Di\Provider\MockProvider;
use TomVerran\Di\ProviderContainer;
use TomVerran\Di\Registry\InterfaceRegistry;
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

    /**
     * @var ProviderContainer
     */
    private $providerContainer;

    public function setUp()
    {
        $this->container = new AggregateContainer;
        $this->singletonContainer = $this->container->get( SingletonRegistry::class );
        $this->providerContainer = $this->container->get( ProviderRegistry::class );
    }

    /**
     * make sure we can resolve the singleton container
     * @throws \TomVerran\Di\Exception\NotFoundException
     */
    public function testSingletonContainerIsResolvable()
    {
        $this->assertInstanceOf( SingletonContainer::class, $this->singletonContainer );
    }

    public function testContainerInterfaceisResolvable()
    {
        $this->assertTrue( $this->container->has( ContainerInterface::class ) );
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

    public function testSingletonContainerHasInterfaceContainer()
    {
        $this->assertTrue( $this->singletonContainer->has( InterfaceRegistry::class ) );
    }

    /**
     * All provider objects should be auto-registered as singletons on first use
     */
    public function testAllProvidersAreSingletons()
    {
        $this->providerContainer->add( stdClass::class, MockProvider::class );
        $this->container->get( stdClass::class );
        $this->assertTrue( $this->singletonContainer->has( MockProvider::class ) );
    }
}