<?php
namespace tomverran\di\Provider;
use stdClass;

class MockProvider implements ProviderInterface
{
    /**
     * @var bool
     */
    private $wasCalled = false;

    /**
     * Was this provider called
     * @return bool
     */
    public function wasCalled()
    {
        return $this->wasCalled;
    }

    /**
     * Get a concrete instance
     * @return mixed
     */
    public function get()
    {
        $this->wasCalled = true;
        return new stdClass;
    }
}