# vBulletin bbCode Parser

> Parser package for vBulletin bbCode

# Contents

- [Installation](#install)
- [Usage](#usage)
    - [Basic Usage](#basic-usage)
    - [Custom Tags](#custom)
- [Supported Tags](#tags)
- [Running Tests](#tests)
- [Contributing](#contributing)

# <a name="install"></a> Installation

```
composer require galahad/vbulletin-bbcode-parser
```

# <a name="usage"></a> Usage

## <a name="basic-usage"></a> Basic Usage

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

## <a name="custom"></a> Custom Tags

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

# <a name="tags"></a> Supported Tags

All supported tags you can find on this URL http://www.vbulletin.org/forum/misc.php?do=bbcode.

Some tags return code based on the `Bootstrap` framework, like `[warning]`, that returns a `<div class="alert alert-warning">` element.

| Tags | Name | Status |
|---------------------------|-------------------------------------------|--------------------------|
| `[b]`, `[i]`, `[u]`, `[s]` | Bold / Italic / Underline / Strikethrough | Passing |
| `[color]` | Color | Passing |
| `[size]` | Font Size | Passing |
| `[font]` | Font | Passing |
| `[highlight]` | Highlight | Passing |
| `[left]`, `[right]`, `[center]` | Left / Right / Center | Passing |
| `[indent]` | Indent | Passing |
| `[email]` | Email Linking | Passing |
| `[url]` | URL Hyperlinking | Passing |
| `[thread]` | Thread Linking | Passing |
| `[post]` | Post Linking | Passing |
| `[list]` | Bulleted Lists / Advanced Lists | Passing |
| `[img]` | Images | Passing |
| `[code]` | Code | Passing |
| `[php]` | PHP Code | Missing Syntax Highlight |
| `[html]` | HTML Code | Missing Syntax Highlight |
| `[quote]` | Quote | Passing |
| `[noparse]` | Stop BB Code Parsing | Passing |
| `[attach]` | Attachment | Passing |
| `[a]` | Anchor | Passing |
| `[align]` | Align | Passing |
| `[floatright]` | Float Right | Passing |
| `[h2]` | Header 2 | Passing |
| `[h3]` | Header 3 | Passing |
| `[high]` | High | Passing |
| `[hr]` | Hr | Passing |
| `[imglft]` | Float Left Image | Passing |
| `[imgrft]` | Float Right Image | Passing |
| `[jira]` | Jira | Add more tests |
| `[lft]` | Float Left | Passing |
| `[minicode]` | Minicode | Missing |
| `[name]` | Name | Passing |
| `[node]` | Node | Missing |
| `[note]` | Note | Passing |
| `[pre]` | Pre Tag | Passing |
| `[process]` | Process | Passing |
| `[rft]` | Float Right | Passing |
| `[warning]` | Warning | Passing |

# <a name="tests"></a> Running Tests

To run all unit tests just execute:

```
./vendor/bin/phpunit
```

Or just `phpunit` if you are using it globally.

# <a name="contributing"></a> Contributing

All contributions are welcome. Before submitting your Pull Request (PR) take a look on the following guidelines:

- Make your changes in a new git branch, based on the `development` branch: `git checkout -b my-fix-branch development`;
- Create your patch/feature, including appropriate test cases. Tests are necessary to make sure what you did is working and did not break nothing in the code;
- Run the unit tests and ensure that all tests are passing;
- In GitHub, send a pull request to the `development` branch, **always**, never send a PR to the `master` branch;
- Make sure your code is following the `PSR-2` conventions (http://www.php-fig.org/psr/psr-2/).
