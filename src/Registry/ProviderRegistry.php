<?php
namespace tomverran\di\Registry;

interface ProviderRegistry
{
    /**
     * Put a provider class into
     * @param string $id The ID of the provider
     * @param string $provider a class name of a provider
     */
    public function add( $id, $provider );
} 