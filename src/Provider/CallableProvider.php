<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 10:49
 */
namespace tomverran\di\Provider;

class CallableProvider implements \tomverran\di\Provider
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * Construct this
     * @param callable $callable
     */
    public function __construct( callable $callable )
    {
        $this->callable = $callable;
    }
    /**
     * Get a concrete instance
     * @return mixed
     */
    public function get()
    {
        return call_user_func( $this->callable );
    }
}