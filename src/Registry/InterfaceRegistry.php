<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 21/02/15
 * Time: 13:47
 */

namespace TomVerran\Di\Registry;


interface InterfaceRegistry
{
    /**
     * Add an interface to the given registry,
     * binding it to a given implementation
     * @param string $interface
     * @param string $implementation
     */
    public function add( $interface, $implementation );
} 