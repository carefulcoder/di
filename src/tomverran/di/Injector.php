<?php
/**
 * Injector.php
 * @author Tom
 * @since 15/11/13
 */

namespace tomverran\di;

/**
 * Class Injector. A Simple and fun DI container.
 * Yes, I said fun. Don't look at me like that.
 * @package Framework
 */
class Injector
{
    /**
     * Instances of singletons
     * @var array
     */
    protected $singletonInstances = array();

    /**
     * Array of class name => objects to return
     * @var array
     */
    protected $boundClasses = array();

    /**
     * Cache of reflection classes
     * @var \ReflectionClass[]
     */
    protected $rcs = array();

    /**
     * Bind an object to a classname class such that the specific instance will be passed to all usages
     * @param string|object|callable $object An object, a class name, or a callable to provide an object
     * @param string $class A class name to bind to

     */
    public function bind($object, $class = null)
    {
        if (!$class && is_object($object) && !is_callable($object)) {
            $class = get_class($object);
        }
        $this->boundClasses[$class] = $object;
    }

    /**
     * Unbind all bound classes
     */
    public function unbindAll()
    {
        $this->boundClasses = array();
    }

    /**
     * Resolve a class, injecting its dependencies
     * @param string|\ReflectionClass $class The class to resolve
     * @param string|null $requester name of the class this is a dependency for
     * @throws \Exception If a param can't be resolved
     * @return object The resolved object
     */
    public function resolve($class, $requester = '')
    {
        //did we get called with an rc
        if ($class instanceof \ReflectionClass) {
            $this->rcs[$class->getName()] = $class;
            $class = $class->getName();
        }

        //did we get called with an object
        if (is_object($class)) {
            return $class;
        }

        //if we have a bound class our job is easy
        if (isset($this->boundClasses[$class])) {

            $boundValue = $this->boundClasses[$class];

            //case 1 - a string classname, though make sure it isn't a calllable global function
            if (!is_callable($boundValue) && is_string($boundValue) && class_exists($boundValue)) {
                return $this->resolve($boundValue, $requester);
            }

            //case 2 - a callable provider
            if (is_callable($boundValue)) {
                return $this->resolve($boundValue($class, $requester), $class);
            }

            //case 3 - an actual object
            if (is_object($boundValue)) {
                return $boundValue;
            }
        }

        //find a reflection class
        if (!isset($this->rcs[$class])) {
            $this->rcs[$class] = new \ReflectionClass($class);
        }

        $rc = $this->rcs[$class];
        $constructor = $rc->getConstructor();

        //load classes that have no constructor defined
        if (!$constructor && $rc->isInstantiable()) {
            return $rc->newInstance();
        }

        //handle loading singleton classes as singletons
        if (!$rc->isInstantiable() && $rc->hasMethod('getInstance')) {
            $this->singletonInstances[$class] = $rc->getMethod('getInstance')->invoke(null);
            return $this->singletonInstances[$class];
        }

        //find any dependencies to load
        $params = array();

        foreach ($constructor->getParameters() as $param) {

            //do we have a default? That'd make life easy
            if ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
                continue;
            }

            //ok then, do we have a type hint?
            if ($typeHinted = $param->getClass()) {
                $params[] = $this->resolve($typeHinted, $class);
            } else {

                //no default, no type hint, there is nothing we can do here but die horribly
                throw new \Exception('No default value for non type hinted param ' . $param->getName());
            }
        }
        return $rc->newInstanceArgs($params);
    }
} 