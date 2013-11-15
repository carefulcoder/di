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
         * @param Dependency $dependency <-- The injector gets "Dependency" from this docblock
         */
        public function __construct($dependency)
        {

        }
    }

    $injector = new \tomverran\di\Injector();
    $instance = $injector->resolve('Foo'); //constructs Dependency, then constructs Foo & passes along Dependency

Pun name candidates
-------------------

"Live and Let DI" - I like this but it is rather a long name
"DI Another Day"  - I'm not ready to admit that Die Another Day existed

"Lady DI" - Bad taste.

"Euthanasia / Helping you DI" - Very bad taste.


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

