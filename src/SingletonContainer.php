<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 12:42
 */

namespace TomVerran\Di;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use TomVerran\Di\Registry\SingletonRegistry;

class SingletonContainer implements ContainerInterface, SingletonRegistry
{
    /**
     * @var Object[] of class => object
     */
    private $singletons;

    /**
     * @var ReflectionContainer
     */
    private $reflectionContainer;

    /**
     * Construct this Singleton Container
     * @param ContainerInterface $rc
     */
    public function __construct( ContainerInterface $rc )
    {
        $this->reflectionContainer = $rc;
        $this->singletons = [];
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
        if (!$this->has( $id ) ) {
            throw new Exception\NotFoundException;
        }

        $singletonObject = $this->singletons[$id] ?: $this->reflectionContainer->get( $id );
        $this->singletons[$id] = $singletonObject;
        return $singletonObject;
    }

    /**
     * Mark a class as a singleton
     * @param int $id The id to refer to this by
     * @param mixed $instance The instance of the singleton
     */
    public function add( $id, $instance = null )
    {
        $this->singletons[$id] = $instance;
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
        return array_key_exists( $id, $this->singletons );
    }
}