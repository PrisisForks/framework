## Our Backward Compatibility Promis

Ensuring smooth upgrades of your projects is our first priority. That’s why
we promise you backward compatibility (BC) for all minor Narrowspark releases.
You probably recognize this strategy as [Semantic Versioning][1].
> In short, Semantic Versioning means that only major releases (such as 2.0, 3.0 etc.) are
allowed to break backward compatibility. Minor releases (such as 2.5, 2.6 etc.)
may introduce new features, but must do so without breaking the existing API of
that release branch (2.x in the previous example).

However, backward compatibility comes in many different flavors. In fact, almost
every change that we make to the framework can potentially break an app.
For example, if we add a new method to a class, this will break an app
which extended this class and added the same method, but with a different
method signature.

Also, not every BC break has the same impact on app code. While some BC
breaks require you to make significant changes to your classes or your
architecture, others are fixed as easily as changing the name of a method.

That’s why we created this page for you. The section "Using Narrowspark Code" will
tell you how you can ensure that your app won’t break completely when
upgrading to a newer version of the same major release branch.

The second section, "Working on Narrowspark Code", is targeted at Narrowspark
contributors. This section lists detailed rules that every contributor needs to
follow to ensure smooth upgrades for our users.

> :warning:
> [Experimental Features][2] and code
> marked with the ``@internal`` tags are excluded from our Backward
> Compatibility promise.
>
> Also note that backward compatibility breaks are tolerated if they are
> required to fix a security issue.

### Using Narrowspark Code

If you are using Narrowspark in your projects, the following guidelines will help
you to ensure smooth upgrades to all future minor releases of your Narrowspark
version.

### Using our Interfaces

All interfaces shipped with Narrowspark can be used in type hints. You can also call
any of the methods that they declare. We guarantee that we won’t break code that
sticks to these rules.

> The exception to this rule are interfaces tagged with ``@internal``. Such interfaces should not be used or implemented.

If you implement an interface, we promise that we won’t ever break your code.

The following table explains in detail which use cases are covered by our
backward compatibility promise:

| Use Case                                      | Backward Compatibility      |
|-----------------------------------------------|-----------------------------|
| **If you...**                                 | **Then we guarantee BC...** |
| Type hint against the interface               | Yes                         |
| Call a method                                 | Yes                         |
| **If you implement the interface and...**     | **Then we guarantee BC...** |
| Implement a method                            | Yes                         |
| Add an argument to an implemented method      | Yes                         |
| Add a default value to an argument            | Yes                         |
| Add a return type to an implemented method    | Yes                         |

### Using our Classes

All classes provided by Narrowspark may be instantiated and accessed through their
public methods and properties.

> :warning:
> Classes, properties and methods that bear the tag ``@internal`` as well as
> the classes located in the various ``*\\Tests\\`` namespaces are an
> exception to this rule. They are meant for internal use only and should
> not be accessed by your own code.

To be on the safe side, check the following table to know which use cases are
covered by our backward compatibility promise:


| Use Case                                      | Backward Compatibility      |
|-----------------------------------------------|-----------------------------|
| **If you...**                                 | **Then we guarantee BC...** |
| Type hint against the class                   | Yes                         |
| Create a new instance                         | Yes                         |
| Extend the class                              | Yes                         |
| Access a public property                      | Yes                         |
| Call a public method                          | Yes                         |
| **If you extend the class and...**            | **Then we guarantee BC...** |
| Access a protected property                   | Yes                         |
| Call a protected method                       | Yes                         |
| Override a public property                    | Yes                         |
| Override a protected property                 | Yes                         |
| Override a public method                      | Yes                         |
| Override a protected method                   | Yes                         |
| Add a new property                            | No                          |
| Add a new method                              | No                          |
| Add an argument to an overridden method       | Yes                         |
| Add a default value to an argument            | Yes                         |
| Call a private method (via Reflection)        | No                          |
| Access a private property (via Reflection)    | No                          |

### Using our Traits

All traits provided by Narrowspark may be used in your classes.

> :Caution!:
>
> The exception to this rule are traits tagged with ``@internal``. Such
> traits should not be used.

To be on the safe side, check the following table to know which use cases are
covered by our backward compatibility promise:

| Use Case                                      | Backward Compatibility      |
|-----------------------------------------------|-----------------------------|
| **If you...**                                 | **Then we guarantee BC...** |
| Use a trait                                   | Yes                         |
| **If you use the trait and...**               | **Then we guarantee BC...** |
| Use it to implement an interface              | Yes                         |
| Use it to implement an abstract method        | Yes                         |
| Use it to extend a parent class               | Yes                         |
| Use it to define an abstract class            | Yes                         |
| Use a public, protected or private property   | Yes                         |
| Use a public, protected or private method     | Yes                         |

### Working on Narrowspark Code

Do you want to help us improve Narrowspark? That’s great! However, please stick
to the rules listed below in order to ensure smooth upgrades for our users.

### Changing Interfaces

This table tells you which changes you are allowed to do when working on
Narrowspark’s interfaces:


|           Type of Change             |        Change Allowed                     |
|--------------------------------------|-------------------------------------------|
| Remove entirely                      |            No                             |
| Change name or namespace             |            No                             |
| Add parent interface                 |            Yes [\[2\]](bc_data_2)            |
| Remove parent interface              |            No                             |
| **Methods**                          |                                           |
| Add method                           |           No                              |
| Remove method                        |           No                              |
| Change name                          |           No                              |
| Move to parent interface             |           Yes                             |
| Add argument without a default value |           No                              |
| Add argument with a default value    |           No                              |
| Remove argument                      |           Yes [\[3\]](bc_data_3)             |
| Add default value to an argument     |           No                              |
| Remove default value of an argument  |           No                              |
| Add type hint to an argument         |           No                              |
| Remove type hint of an argument      |           No                              |
| Change argument type                 |           No                              |
| Add return type                      |           No                              |
| Remove return type                   |           No [\[9\]](bc_data_9)              |
| Change return type                   |           No                              |
| **Static Methods**                   |                                           |
| Turn non static into static          |           No                              |
| Turn static into non static          |           No                              |
| **Constants**                        |                                           |
| Add constant                         |           Yes                             |
| Remove constant                      |           No                              |
| Change value of a constant           |           Yes [1](bc_data_1) [\[5\]](bc_data_5) |


### Changing Classes

This table tells you which changes you are allowed to do when working on
Narrowspark’s classes:

| Type of Change                               |        Change Allowed                                     |
|----------------------------------------------|-----------------------------------------------------------|
| Remove entirely                              |        No                                                 |
| Make final                                   |        No [\[6\]](bc_data_6)                                 |
| Make abstract                                |        No                                                 |
| Change name or namespace                     |        No                                                 |
| Change parent class                          |        Yes [\[4\]](bc_data_4)                                |
| Add interface                                |        Yes                                                |
| Remove interface                             |        No                                                 |
| **Public Properties**                        |                                                           |
| Add public property                          |        Yes                                                |
| Remove public property                       |        No                                                 |
| Reduce visibility                            |        No                                                 |
| Move to parent class                         |        Yes                                                |
| **Protected Properties**                     |                                                           |
| Add protected property                       |        Yes                                                |
| Remove protected property                    |        No [\[7\]](bc_data_7)                                 |
| Reduce visibility                            |        No [\[7\]](bc_data_7)                                 |
| Make public                                  |        No [\[7\]](bc_data_7)                                 |
| Move to parent class                         |        Yes                                                |
| **Private Properties**                       |                                                           |
| Add private property                         |        Yes                                                |
| Make public or protected                     |        Yes                                                |
| Remove private property                      |        Yes                                                |
| **Constructors**                             |                                                           |
| Add constructor without mandatory arguments  |        Yes [1](bc_data_1)                                    |
| Remove constructor                           |        No                                                 |
| Reduce visibility of a public constructor    |        No                                                 |
| Reduce visibility of a protected constructor |        No [\[7\]](bc_data_7)                                 |
| Move to parent class                         |        Yes                                                |
| **Destructors**                              |                                                           |
| Add destructor                               |        Yes                                                |
| Remove destructor                            |        No                                                 |
| Move to parent class                         |        Yes                                                |
| **Public Methods**                           |                                                           |
| Add public method                            |        Yes                                                |
| Remove public method                         |        No                                                 |
| Change name                                  |        No                                                 |
| Reduce visibility                            |        No                                                 |
| Make final                                   |        No [\[6\]](bc_data_6)                                 |
| Move to parent class                         |        Yes                                                |
| Add argument without a default value         |        No                                                 |
| Add argument with a default value            |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove argument                              |        Yes [\[3\]](bc_data_3)                                |
| Add default value to an argument             |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove default value of an argument          |        No                                                 |
| Add type hint to an argument                 |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove type hint of an argument              |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Change argument type                         |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Add return type                              |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove return type                           |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8) [\[9\]](bc_data_9) |
| Change return type                           |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| **Protected Methods**                        |                                                           |
| Add protected method                         |        Yes                                                |
| Remove protected method                      |        No [\[7\]](bc_data_7)                                 |
| Change name                                  |        No [\[7\]](bc_data_7)                                 |
| Reduce visibility                            |        No [\[7\]](bc_data_7)                                 |
| Make final                                   |        No [\[6\]](bc_data_6)                                 |
| Make public                                  |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Move to parent class                         |        Yes                                                |
| Add argument without a default value         |        No [\[7\]](bc_data_7)                                 |
| Add argument with a default value            |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove argument                              |        Yes [\[3\]](bc_data_3)                                |
| Add default value to an argument             |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove default value of an argument          |        No [\[7\]](bc_data_7)                                 |
| Add type hint to an argument                 |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove type hint of an argument              |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Change argument type                         |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Add return type                              |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Remove return type                           |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8) [\[9\]](bc_data_9) |
| Change return type                           |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| **Private Methods**                          |                                                           |
| Add private method                           |        Yes                                                |
| Remove private method                        |        Yes                                                |
| Change name                                  |        Yes                                                |
| Make public or protected                     |        Yes                                                |
| Add argument without a default value         |        Yes                                                |
| Add argument with a default value            |        Yes                                                |
| Remove argument                              |        Yes                                                |
| Add default value to an argument             |        Yes                                                |
| Remove default value of an argument          |        Yes                                                |
| Add type hint to an argument                 |        Yes                                                |
| Remove type hint of an argument              |        Yes                                                |
| Change argument type                         |        Yes                                                |
| Add return type                              |        Yes                                                |
| Remove return type                           |        Yes                                                |
| Change return type                           |        Yes                                                |
| **Static Methods and Properties**            |                                                           |
| Turn non static into static                  |        No [\[7\]](bc_data_7) [\[8\]](bc_data_8)                 |
| Turn static into non static                  |        No                                                 |
| **Constants**                                |                                                           |
| Add constant                                 |        Yes                                                |
| Remove constant                              |        No                                                 |
| Change value of a constant                   |        Yes [\[1\]](bc_data_1) [\[5\]](bc_data_5)                |

### Changing Traits

This table tells you which changes you are allowed to do when working on
Narrowspark’s traits:

| Type of Change                       |        Change Allowed            |
|--------------------------------------|----------------------------------|
| Remove entirely                      |               No                 |
| Change name or namespace             |               No                 |
| Use another trait                    |               Yes                |
| **Public Properties**                |                                  |
| Add public property                  |               Yes                |
| Remove public property               |               No                 |
| Reduce visibility                    |               No                 |
| Move to a used trait                 |               Yes                |
| **Protected Properties**             |                                  |
| Add protected property               |               Yes                |
| Remove protected property            |               No                 |
| Reduce visibility                    |               No                 |
| Make public                          |               No                 |
| Move to a used trait                 |               Yes                |
| **Private Properties**               |                                  |
| Add private property                 |               Yes                |
| Remove private property              |               No                 |
| Make public or protected             |               Yes                |
| Move to a used trait                 |               Yes                |
| **Constructors and destructors**     |                                  |
| Have constructor or destructor       |               No                 |
| **Public Methods**                   |                                  |
| Add public method                    |               Yes                |
| Remove public method                 |               No                 |
| Change name                          |               No                 |
| Reduce visibility                    |               No                 |
| Make final                           |               No [\[6\]](bc_data_6) |
| Move to used trait                   |               Yes                |
| Add argument without a default value |               No                 |
| Add argument with a default value    |               No                 |
| Remove argument                      |               No                 |
| Add default value to an argument     |               No                 |
| Remove default value of an argument  |               No                 |
| Add type hint to an argument         |               No                 |
| Remove type hint of an argument      |               No                 |
| Change argument type                 |               No                 |
| Change return type                   |               No                 |
| **Protected Methods**                |                                  |
| Add protected method                 |               Yes                |
| Remove protected method              |               No                 |
| Change name                          |               No                 |
| Reduce visibility                    |               No                 |
| Make final                           |               No [\[6\]](bc_data_6) |
| Make public                          |               No [\[8\]](bc_data_8) |
| Move to used trait                   |               Yes                |
| Add argument without a default value |               No                 |
| Add argument with a default value    |               No                 |
| Remove argument                      |               No                 |
| Add default value to an argument     |               No                 |
| Remove default value of an argument  |               No                 |
| Add type hint to an argument         |               No                 |
| Remove type hint of an argument      |               No                 |
| Change argument type                 |               No                 |
| Change return type                   |               No                 |
| **Private Methods**                  |                                  |
| Add private method                   |               Yes                |
| Remove private method                |               No                 |
| Change name                          |               No                 |
| Make public or protected             |               Yes                |
| Move to used trait                   |               Yes                |
| Add argument without a default value |               No                 |
| Add argument with a default value    |               No                 |
| Remove argument                      |               No                 |
| Add default value to an argument     |               No                 |
| Remove default value of an argument  |               No                 |
| Add type hint to an argument         |               No                 |
| Remove type hint of an argument      |               No                 |
| Change argument type                 |               No                 |
| Add return type                      |               No                 |
| Remove return type                   |               No                 |
| Change return type                   |               No                 |
| **Static Methods and Properties**    |                                  |
| Turn non static into static          |               No                 |
| Turn static into non static          |               No                 |

<a name="bc_data_1"></a> Should be avoided. When done, this change must be documented in the
       UPGRADE file.

<a name="bc_data_2"></a> The added parent interface must not introduce any new methods that don’t
       exist in the interface already.

<a name="bc_data_3"></a> Only the last argument(s) of a method may be removed, as PHP does not
       care about additional arguments that you pass to a method.

<a name="bc_data_4"></a> When changing the parent class, the original parent class must remain an
       ancestor of the class.

<a name="bc_data_5"></a> The value of a constant may only be changed when the constants aren’t
       used in configuration (e.g. Yaml and XML files), as these do not support
       constants and have to hardcode the value. For instance, event name
       constants can’t change the value without introducing a BC break.
       Additionally, if a constant will likely be used in objects that are
       serialized, the value of a constant should not be changed.

<a name="bc_data_6"></a> Allowed using the ``@final`` annotation.

<a name="bc_data_7"></a> Allowed if the class is final. Classes that received the ``@final``
       annotation after their first release are considered final in their
       next major version.
       Changing an argument type is only possible with a parent type.
       Changing a return type is only possible with a child type.

<a name="bc_data_8"></a> Allowed if the method is final. Methods that received the ``@final``
       annotation after their first release are considered final in their
       next major version.
       Changing an argument type is only possible with a parent type.
       Changing a return type is only possible with a child type.

<a name="bc_data_9"></a> Allowed for the ``void`` return type.

> This work, "Our Backward Compatibility Promise", is a derivative of "Our Backward Compatibility Promise" by [Symfony][3], used under [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/).
> "Our Backward Compatibility Promise" is licensed under [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/) by Narrowspark.

[1]: https://semver.org/
[2]: 05_Experimental.md
[3]: https://symfony.com/doc/current/contributing/code/bc.html
