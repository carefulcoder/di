<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 08/02/15
 * Time: 12:54
 */
namespace tomverran\di\Provider;

interface ProviderInterface
{
    /**
     * Get a concrete instance
     * @return mixed
     */
    public function get();
} 