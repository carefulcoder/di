<?php
/**
 * LazyLoader.php
 * @author Tom
 * @since 15/11/13
 */

namespace tomverran\di;


class LazyLoader implements \ArrayAccess
{
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var object
     */
    private $instance;

    /**
     * @var array
     */
    private $args;

    /**
     * Create this LazyLoader
     * @param \ReflectionClass $reflectionClass
     * @param array $args
     */
    public function __construct( $reflectionClass, $args )
    {
        $this->reflectionClass = $reflectionClass;
        $this->args = $args;
    }

    /**
     * Load a class
     * @return object
     */
    private function load()
    {
        if (!$this->instance) {
            $this->instance = $this->reflectionClass->newInstanceArgs($this->args);
            unset($this->reflectionClass);
        }
        return $this->instance;
    }

    /**
     * Call a method
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {
        call_user_func_array(array($this->load(), $method), $args);
    }

    /**
     * Get a property
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->load()->$property;
    }

    /**
     * Set a property
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->load()->$property = $value;
    }

    /**
     * Whether an offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * An offset to check for.
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        $instance = $this->load();
        return isset($instance[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        $instance = $this->load();
        $instance[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * The offset to assign the value to.
     * @param mixed $value
     * The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $instance = $this->load();
        $instance[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        $instance = $this->load();
        unset($instance[$offset]);
    }
}