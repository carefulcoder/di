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
     * Rogues gallery of classes that are expensive to instantiate
     * and so we should lazy load them with our dodgy wrapper
     * @var string[]
     */
    protected $expensiveClasses = array();

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
     * Flag a class as expensive to instantiate
     * @param string $className
     */
    public function setExpensive($className)
    {
        $this->expensiveClasses[] = $className;
    }

    /**
     * Mock a class such that when it is instantiated the mock will be returned instead
     * @param string $class The classname to mock
     * @param mixed $object The mocked object
     */
    public function bind($class, $object)
    {
        $this->boundClasses[$class] = $object;
    }

    /**
     * Remove all mocks
     */
    public function unbindAll()
    {
        $this->boundClasses = array();
    }

    /**
     * Resolve a class, injecting its dependencies
     * @param string|\ReflectionClass $class The class to resolve
     * @throws \Exception If a param can't be resolved
     * @return object The resolved object
     */
    public function resolve($class)
    {
        if ($class instanceof \ReflectionClass) {
            $this->rcs[$class->getName()] = $class;
            $class = $class->getName();
        }

        //if we have a bound class our job is easy
        if (isset($this->boundClasses[$class])) {
            return $this->boundClasses[$class];
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
            if ($class = $param->getClass()) {
                $params[] = $this->resolve($class);
            } else {

                //no default, no type hint, there is nothing we can do here but die horribly
                throw new \Exception('No default value for non type hinted param ' . $param->getName());
            }
        }
        return $rc->newInstanceArgs($params);
    }
} 