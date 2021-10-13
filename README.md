# psalm-elvis-begone
A [Psalm](https://github.com/vimeo/psalm) plugin to replace Elvis operator (?:) by null coalesce operator (??) when applicable

Installation:

```console
$ composer require --dev orklah/psalm-elvis-begone
$ vendor/bin/psalm-plugin enable orklah/psalm-elvis-begone
```

Usage:

Run Psalter command:
```console
$ vendor/bin/psalm --alter --plugin=vendor/orklah/psalm-elvis-begone/src/Plugin.php
```

Explanation:

The short ternary operator (or Elvis operator ?: ) is used to evaluate return its condition if it's true or the second operand if it's not.

When the type is known and the only falsy value is null, we can actually replace it with the null coalesce operator (??) for strictness and clarity.

It will prevent future values to be evaluated to false when it was not the intention
