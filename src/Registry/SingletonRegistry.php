<?php
namespace tomverran\di\Registry;

interface SingletonRegistry
{
    /**
     * Mark a class as a singleton
     * @param int $id The id to refer to this by
     * @param mixed $instance The instance of the singleton
     */
    public function add( $id, $instance = null );
} 