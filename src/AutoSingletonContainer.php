<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 21/02/15
 * Time: 13:20
 */

namespace TomVerran\Di;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

/**
 * This is like a SingletonContainer except that
 * it will automatically flag any object instances requested through it
 * as being singletons
 *
 * Class AutoSingletonContainer
 * @package TomVerran\Di
 */
class AutoSingletonContainer implements ContainerInterface
{
    /**
     * Construct this AutoSingletonContainer
     * @param SingletonContainer $container
     */
    public function __construct( SingletonContainer $container )
    {
        $this->container = $container;
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
        $this->container->add( $id );
        return $this->container->get( $id );
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
        $this->container->add( $id );
        return $this->container->has( $id );
    }
}
