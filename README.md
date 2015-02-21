Itty Bitty DI container
=======================

The point of this is to do something like other DI containers but without the need for boring typing hinting.
So this figures out dependencies to inject from the docblock rather than typehints so you can mock a dependency
with whatever you want because PHP isn't a strictly typed language.

I mainly made this as a bit of fun so don't worry I'm not going to start advocating this over more mature solutions
like those offered by things like Symfony or Laravel

Well, not that much.

Usage
------
```php
/**
 * Some random dependency
 */
class Dependency
{
    public function __construct()
    {
        //stuff
    }
}

/**
 * Some dependent class
 */
class Foo
{
    /**
     * @param Dependency $dependency
     */
    public function __construct(Dependency $dependency)
    {

    }
}

$injector = new \TomVerran\Di\AggregateContainer();
$instance = $injector->get( Foo::class );
```

Singletons
----------

Singleton classes are handled by the ```SingletonContainer``` object.
To tag a class as being a Singleton you should use the ```AggregateContainer``` to get
an instance of a ```SingletonRegistry``` object and use that.

```php

/**
 * An example of a class you could create to configure Singletons
 */
class SingletonConfiguration 
{
    private $singletons;

    public function __construct( SingletonRegistry $r )
    {
        $this->singletons = $r;
    }
    
    public function configure()
    {
        $this->singletons->add( SingletonClassName::class );
        $this->singletons->add( SingletonClassNameTwo::class );
        $this->singletons->add( SomeOtherClass::class );
    }

}
```

then in the entry point for your framework

```php
$singletonConfig = $aggregateContainer->get(SingletonConfiguration::class);
$singletonConfig->configure();
```

License
-------

tl;dr MIT license

Copyright (c) 2013 Tom Verran

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

