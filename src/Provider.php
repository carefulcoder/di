<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 10:31
 */

namespace tomverran\di;


interface Provider
{
    /**
     * Get a concrete instance
     * @return mixed
     */
    public function get();
} 