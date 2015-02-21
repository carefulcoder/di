<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 12:51
 */

namespace tomverran\di;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use TomVerran\ParameterResolver;
use ReflectionClass;

class ReflectionContainer implements ContainerInterface
{
    /**
     * @var ParameterResolver
     */
    private $parameterResolver;

    /**
     * Construct this ReflectionContainer
     * @param ParameterResolver $pr
     */
    public function __construct( ParameterResolver $pr )
    {
        $this->parameterResolver = $pr;
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
        $rc = new ReflectionClass( $id );
        $constructor = $rc->getConstructor();
        $reflectionParameters = $constructor ? $constructor->getParameters() : [];
        $parameters = $this->parameterResolver->resolveParameters( $reflectionParameters );
        return $rc->newInstanceArgs( $parameters );
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
        return class_exists( $id );
    }
}