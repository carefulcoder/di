DI container
============

This is a dependency injection container built upon the excellent ```ContainerInterface``` interface
for maximum interoperability. It has been designed with SOLID principles in mind, most notably the single responsibility principle.

Each of the three roles of the container (getting object instances with reflection, getting singleton objects and getting objects with providers)
are handled with individual containers which are then composed together with an ```AggregateContainer``` which is the main entry point to the library.

Basic Usage
-----------

```php
/**
 * Some class you depend on
 */
class Dependency
{
    public function __construct()
    {
        //stuff
    }
}

/**
 * Some class with dependencies
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

Interfaces or Abstract classes
------------------------------

Non concrete classes are handled by the ```InterfaceContainer``` object.
To bind an interface or abstract class to a concrete class you should use the ```AggregateContainer``` to get
an instance of an ```InterfaceRegistry``` object and call the ```add($interface, $implementation)``` method
to perform the binding

```php
$interfaceRegistry = $aggregateContainer->get( SingletonRegistry::class );
$interfaceRegistry->add( SomeInterface::class, SomeImplementation::class );
```

Singletons
----------

Singleton classes are handled by the ```SingletonContainer``` object.
To tag a class as being a Singleton you should use the ```AggregateContainer``` to get
an instance of a ```SingletonRegistry``` object and call the ```add($class)``` method
to flag a class as being a singleton.

```php
$singletonRegistry = $aggregateContainer->get( SingletonRegistry::class );
$singletonRegistry->add( SingletonClassName::class );
$singletonRegistry->add( SingletonClassName::class );
```

Providers
---------

Providers allow you to call on an object to get an instance of a class
when it is otherwise impossible to resolve. For example a class with scalar parameters
will need to have a provider fill those parameters in. Providers implement ```ProviderInterface```.

To flag a class as being provided by a provider you should use the ```AggregateContainer``` to get
an instance of a ```ProviderRegistry``` object and call the ```add($class, $provider)``` method
to flag a class as being provided by another class.

```php
/**
 * An example provider
 */
class Provider implements ProviderInterface
{
    private $dependency;

    public function __construct( Dependency $d )
    {
        $this->dependency = $d;
    }
    
    public function get()
    {
        return new DependencyWithScalarArguments( "something", $this->dependency );
    }
}
```
then in the entry point for your framework

```php
$providerContainer = $aggregateContainer->get( ProviderContainer::class );
$providerContainer->add( DependencyWithScalarArguments::class, Provider::class );
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

