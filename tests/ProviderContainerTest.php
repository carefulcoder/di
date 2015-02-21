<?php
use TomVerran\Di\Provider\MockProvider;
use TomVerran\Di\ProviderContainer;
use TomVerran\MockContainer;

class ProviderContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockProvider
     */
    private $mockProvider;

    /**
     * @var ProviderContainer
     */
    private $providerContainer;

    /**
     * Set up before this unit test
     */
    public function setUp()
    {
        $this->mockProvider = new MockProvider;
        $mockContainer = new MockContainer( [MockProvider::class => $this->mockProvider] );
        $this->providerContainer = new ProviderContainer( $mockContainer );
    }

    /**
     * test that when we resolve a class with the provider container
     * what it actually does is retrieve the object from the provider
     */
    public function testProviderGetsCalled()
    {
        $this->providerContainer->add( stdClass::class, MockProvider::class );
        $out = $this->providerContainer->get( stdClass::class );

        $this->assertInstanceOf( stdClass::class, $out );
        $this->assertTrue( $this->mockProvider->wasCalled(), 'Mock provider was called' );
    }
} 