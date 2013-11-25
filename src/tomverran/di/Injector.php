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
     * Array of class name => mock objects to return
     * @var array
     */
    protected $mocks = array();

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
    public function mock($class, $object)
    {
        $this->mocks[$class] = $object;
    }

    /**
     * Remove all mocks
     */
    public function clearMocks()
    {
        $this->mocks = array();
    }

    /**
     * Get an instance of an object depending on laziness
     * @param \ReflectionClass $rc The reflection class to consider instantiating
     * @param array $args An array of parameters to contemplate passing to the class
     * @param bool $lazy Whether we can be bothered with the hassle of instantiation
     * @return LazyLoader|object
     */
    protected function getObject($rc, $args, $lazy = false)
    {
        return $lazy ? new LazyLoader($rc, $args) : $rc->newInstanceArgs($args);
    }

    /**
     * Resolve a class, injecting its dependencies
     * @param string $class The class to resolve
     * @param bool $forceLazy Whether to force lazy loading
     * @return object The resolved object
     */
    public function resolve($class, $forceLazy = false)
    {
        //we want to lazy load if it has been forced on us or if this class is £££
        $lazy = $forceLazy || in_array($class, $this->expensiveClasses);

        //if we have a mock our job is easy
        if (isset($this->mocks[$class])) {
            return $this->mocks[$class];
        }

        //find a reflection class
        if (!isset($this->rcs[$class])) {
            $this->rcs[$class] = new \ReflectionClass($class);
        }

        $matches = array();
        $rc = $this->rcs[$class];
        $constructor = $rc->getConstructor();

        //load classes that have no constructor defined
        if (!$constructor && $rc->isInstantiable()) {
            return $this->getObject($rc, array(), $lazy);
        }

        //handle loading singleton classes as singletons
        if (!$rc->isInstantiable() && $rc->hasMethod('getInstance')) {
            $this->singletonInstances[$class] = $rc->getMethod('getInstance')->invoke(null);
            return $this->singletonInstances[$class];
        }

        //find any dependencies to load
        $params = array();

        foreach ($constructor->getParameters() as $param) {

            //first, do we have a type hint?
            if ($class = $param->getClass()) {
                $params[] = $this->resolve($class, $lazy);
            } else {

                //no type hint, do we have a default?
                if ($default = $param->getDefaultValue()) {
                    $params[] = $default;
                }

                //no default, no type hint, there is nothing we can do here but die horribly
                throw new \Exception('No default value for non type hinted param ' . $param->getName());
            }

        }
        return $this->getObject($rc, $params, $lazy);
    }
} 