# vBulletin BBcode Parser

> Parse your vBulletin bbcodes in a very easy way.

# Contents

- [Installation](#install)
- [Usage](#usage)
    - [Basic Usage](#basic-usage)
    - [Custom Tags](#custom)
- [Supported Tags](#tags)
- [Running Tests](#tests)
- [Contributing](#contrib)
- [License](#license)

# <a id="install"></a> Installation

```
composer require galahad/vbulletin-bbcode-parser
```

# <a id="usage"></a> Usage

## <a id="basic-usage"></a> Basic Usage

```php
$bbcode = '[font=Times New Roman]foo bar text[/font]';

$parser = new Parser;
echo $parser->parse($bbcode); // <span style="font-family: Times New Roman;">foo bar text</span>
```

To generate bbcodes with URLs, like `[post]` and `[thread]` just add an array of urls in the `__constructor`:

```php
$parser = new Parser([
    'thread_url' => 'http://example.com/thread/{thread_id}/bar',
    'post_url' => 'http://example.com/posts/{post_id}',
    'attach_url' => 'http://example.com/attach/{attach_id}',
    'jira_url' => 'http://tracker.vbulletin.com/browse/{jira_id}',
    'user_url' => 'http://example.com/users/show/{user_id}',
]);

$parser->parse('[post=269302]Click Me![/post]');
```

> To see all tags supported by this package take a look on [Supported Tags](#tags) section.

## <a id="custom"></a> Custom Tags

If you have a custom tag you want to customize, or even override the default behaviour of an internal one, just `extend` with your own tag name:

```php
$parser = new Parser;

$parser->extend('foo', function ($block, array $attributes, $content) {
    return '<a href="http://foo.com/bar">' . $content . '</a>';
});

echo $parser->parse('[foo]some text here[/foo]');
```

As parameters received from the `extend()` method you have:

- `string $block`: the entire bbcode passed like `[foo]some text here[/foo]` in this case;
- `array $attributes`: an key/value array with all attributes we found in your bbcode, like `['font' => 'Times New Roman']` for the bbcode `[font=Times New Roman]foo bar text[/font]`;
- `mixed $content`: the tag content, like `some text here`.

You can also extend our parser using a custom class. It might have a `render()` method receiving `$block`, `$attributes` and `$content` as parameters, and might implements `Galahad\Bbcode\Tags\CustomTagInterface` interface.

```php
$parser = new Parser;
$parser->extend('foo', Foo\Bar\FooTag::class);
```