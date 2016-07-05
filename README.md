# zf2-cli

[![Build Status](https://secure.travis-ci.org/yalesov/zf2-cli.png)](http://travis-ci.org/yalesov/zf2-cli)

helper functions for building a zf2 CLI app

# Installation

[Composer](http://getcomposer.org/):

```json
{
    "require": {
        "yalesov/zf2-cli": "1.*"
    }
}
```

Copy `config/cli.local.php.dist` into `(app root)/config/autoload/cli.local.php`, and edit configs as described below in **Setup templates**.

# Usage

## Get an instance of cli

Use service locator.

```php
$cli = $locator->get('cli');
$cli = $locator->get('Yalesov\Cli\Cli');
```

Or just instantiate one manually.

```php
$cli = new \Yalesov\Cli\Cli;
```

## Templated-text

Often you need to display similarly-formatted text in a CLI app, e.g. section titles.

### Setup templates

Add a template named `section`, which outputs a blue string `## %s ##` (`%s` replaced by actual text).

```php
$cli->addTemplate('section', array(
    'template'  => '## %s ##',
    'color'     => 'BLUE',
));
```

- `template`: (optional), `%s` as text placeholder; not set = `'%s'` (plain text)
- `color`: (optional), a `\Zend\Console\ColorInterface` defined constant name; not set = default / normal color

You can also inject templates through the config file:

```php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'cli' => 'Yalesov\Cli\Cli',
            ),
            'cli' => array(
                'parameters' => array(
                    'templates' => array (
                        'section' => array(
                            'template'  => '## %s ##',
                            'color'     => 'YELLOW',
                        ),
                        'task' => array (
                            'template'  => '- %s -',
                            'color'     => 'BLUE',
                        ),
                        'module' => array(
                            'template'  => '[ %s ]',
                            'color'     => 'GREEN',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
```

This will setup the templates `section`, `task`, and `module`.

### Output text

```php
$cli->write('foo', 'section');
```

This will output the string `foo` using the `section` template. In the above example setup, this will output `## foo ##`, with yellow color.

You can also capture the output string instead of printing it directly:

```php
$string = $cli->write('foo', 'section', false);
```
