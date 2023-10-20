# Rector Rules

Some custom Rector rules that could be useful some day, or just as exercises.

[List of rules](./docs/rules_overview.md)


## Creating a new rule

It's possible to generate a boilerplate for a new Rector rule thanks to [Rector generator](https://github.com/rectorphp/rector-generator).

Edit the `rector-recipe.php` file to describe your new rule and run `./bin/rector generate`.
If that file doesn't exist, use `vendor/bin/rector init-recipe` to create it.

Don't overthink what you're writing there, you'll be able to edit the result later.

Rector generator is made for the main Rector repository. You'll need to edit the namespace of the generated rule, changing `\Rector` to `\SelrahcD\RectorRules` and edit the `configured_rule.php` created for testing purpose to match the new namespace.
