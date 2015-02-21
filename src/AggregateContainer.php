<?php
namespace TomVerran\Di;
use Interop\Container\ContainerInterface;
use TomVerran\ContainerParameterResolver;
use TomVerran\Di\Exception\NotFoundException;
use TomVerran\Di\Registry\ProviderRegistry;
use TomVerran\Di\Registry\SingletonRegistry;
use TomVerran\ParameterResolver;

/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 13:06
 */

class AggregateContainer implements ContainerInterface
{
    /**
     * @var ReflectionContainer
     */
    private $reflectionContainer;

    /**
     * @var SingletonContainer
     */
    private $singletonContainer;

    /**
     * @var ProviderContainer
     */
    private $providerContainer;

    /**
     * @var ContainerInterface[]
     */
    private $containers;

    /**
     * Construct this AggregateContainer
     */
    public function __construct()
    {
        $parameterResolver = new ContainerParameterResolver( $this );
        $this->reflectionContainer = new ReflectionContainer( $parameterResolver );
        $this->singletonContainer = new SingletonContainer( $this->reflectionContainer );
        $this->providerContainer = new ProviderContainer( $this->singletonContainer );

        // access to each of the underlying container types can be done through the container itself
        $this->singletonContainer->add( SingletonRegistry::class, $this->singletonContainer );
        $this->singletonContainer->add( ProviderRegistry::class, $this->providerContainer );
        $this->singletonContainer->add( ParameterResolver::class, $parameterResolver );

        $this->containers = [
            $this->providerContainer,
            $this->singletonContainer,
            $this->reflectionContainer
        ];
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
        foreach( $this->containers as $container ) {
            if ( $container->has( $id ) ) {
                return $container->get( $id );
            }
        }
        throw new NotFoundException;
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
        foreach ( $this->containers as $container ) {
            if ( $container->has( $id ) ) {
                return true;
            }
        }
        return false;
    }
}