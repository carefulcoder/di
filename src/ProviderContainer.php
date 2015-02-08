<?php
namespace tomverran\di;
use Interop\Container\ContainerInterface;
use tomverran\di\Exception\NotFoundException;
use tomverran\di\Provider\ProviderInterface;
use tomverran\di\Registry\ProviderRegistry;
use tomverran\di\Provider;

class ProviderContainer implements ContainerInterface, ProviderRegistry
{
    /**
     * @var string[]
     */
    private $providers;

    /**
     * Construct this ProviderContainer
     * @param ContainerInterface $sc A singleton container
     */
    public function __construct( ContainerInterface $sc )
    {
        $this->singletonContainer = $sc;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws \Interop\Container\Exception\NotFoundException  No entry was found for this identifier.
     * @throws \Interop\Container\Exception\ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get( $id )
    {
        if ( !$this->has( $id ) ) {
            throw new NotFoundException;
        }

        /** @var ProviderInterface $provider */
        $provider = $this->singletonContainer->get( $this->providers[$id] );
        return $provider->get();
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has( $id )
    {
        return isset( $this->providers[$id] );
    }

    /**
     * Put a provider class into
     * @param string $id The ID of the provider
     * @param $provider a classname of a provider
     */
    public function add( $id, $provider )
    {
        $this->providers[$id] = $provider;
    }
}