<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 21/02/15
 * Time: 13:45
 */

namespace TomVerran\Di;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use TomVerran\Di\Registry\InterfaceRegistry;

class InterfaceContainer implements ContainerInterface, InterfaceRegistry
{
    /**
     * An array of interfaces to implementations
     * @var array
     */
    private $mappings;

    /**
     * Construct this InterfaceContainer
     * @param ContainerInterface $ci
     */
    public function __construct( ContainerInterface $ci )
    {
        $this->container = $ci;
        $this->mappings = [];
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get( $id )
    {
        if ( !$this->has( $id ) ) {
            throw new Exception\NotFoundException;
        }
        return $this->container->get( $this->mappings[$id] );
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
        return array_key_exists( $id, $this->mappings );
    }

    /**
     * Add an interface to the given registry,
     * binding it to a given implementation
     * @param string $interface
     * @param string $implementation
     */
    public function add( $interface, $implementation )
    {
        $this->mappings[$interface] = $implementation;
    }
}