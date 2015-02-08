<?php
/**
 * Injector.php
 * @author Tom
 * @since 15/11/13
 */

namespace tomverran\di;
use Interop\Container\ContainerInterface;
use TomVerran\ContainerParameterResolver;

/**
 * Class Container. A Simple and fun DI container.
 * Yes, I said fun. Don't look at me like that.
 * @package Framework
 */
class Container implements ContainerInterface
{
    /**
     * Array of class name => class name
     * @var array
     */
    protected $boundClasses = [];

    /**
     * Array of class name => object
     * @var array
     */
    protected $boundObjects = [];

    /**
     * Array of class name => provider
     * @var Provider[]
     */
    protected $boundProviders = [];

    /**
     * Cache of reflection classes
     * @var \ReflectionClass[]
     */
    protected $rcs = [];

    /**
     * We use this to resolve parameters for classes
     * @var ContainerParameterResolver
     */
    private $parameterResolver;

    /**
     * Construct this DI container
     * this has a dodgy hard dependency
     */
    public function __construct()
    {
        $this->parameterResolver = new ContainerParameterResolver( $this );
    }

    /**
     * Bind a class to another class
     * @param string $interface The interface or abstract class name
     * @param string $implementation The concrete class to instantiate
     */
    public function bind( $interface, $implementation )
    {
        $this->boundClasses[$interface] = $implementation;
    }

    /**
     * Bind a class to a specific instance of an object
     * @param string $interface The interface or abstract class name
     * @param Object $object The object to return
     */
    public function bindObject( $interface, $object )
    {
        $this->boundObjects[$interface] = $object;
    }

    /**
     * Bind a class to an object returned by calling a provider
     * @param string $interface The interface or abstract class name
     * @param Provider $provider The provider to call to retrieve an instance
     */
    public function bindProvider( $interface, Provider $provider )
    {
        $this->boundProviders[$interface] = $provider;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @throws Exception\ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get( $id )
    {
        try {
            if ( $object = $this->resolveFromBoundValue( $id ) ) {
                return $object;
            }

            $rc = $this->getReflectionClass( $id );
            $constructor = $rc->getConstructor();

            $reflectionParameters = $constructor ? $constructor->getParameters() : [];
            $parameters = $this->parameterResolver->resolveParameters( $reflectionParameters );
            return $rc->newInstanceArgs( $parameters );

        } catch ( \Exception $e ) {
            throw new Exception\ContainerException( "", 1, $e );
        }
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
        $hasClass = isset( $this->boundClasses[$id] );
        $hasObject = isset( $this->boundObjects[$id] );
        $hasProvider = isset( $this->boundProviders[$id] );

        if ( !$hasProvider && !$hasClass && !$hasObject ) {
            try {
                $this->get( $id );
                return true;
            } catch (Exception\ContainerException $e ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $class
     * @return \ReflectionClass
     */
    private function getReflectionClass( $class )
    {
        if ( !isset( $this->rcs[$class] ) ) {
            $this->rcs[$class] = new \ReflectionClass($class);
        }

        $rc = $this->rcs[$class];
        return $rc;
    }

    /**
     * Resolve an object from a class using bound values
     * @param string $class The class to resolve
     * @return null|object an object or null
     */
    private function resolveFromBoundValue( $class )
    {
        if ( isset( $this->boundClasses[$class] ) ) {
            return $this->get( $this->boundClasses[$class] );
        }

        if ( isset( $this->boundObjects[$class] ) ) {
            return $this->boundObjects[$class];
        }

        if ( isset( $this->boundProviders[$class] ) ) {
            return $this->boundProviders[$class]->get();
        }

        return null;
    }
}