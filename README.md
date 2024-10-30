# vicco

vicco is brutalist microblogging software contained in a single PHP file.

## Features

* Responsive layout
* Web interface to publish, edit, and delete posts
* Text formatting
* Atom feed (somewhat broken)
* No ActivityPub support

## Target audiences

* Hackers — It’s easy to customize.
* Hipsters — It’s crappy, you could use it as a joke.

## Requirements

* Web server with PHP
* SSL/TLS certificate

## Installation

1. Adjust the config in `index.php`, especially your credentials.
2. Upload it to your server and make the directory writable.
3. Open the site in your browser.
4. It should work.

## Text formatting

There is some [Markdown](https://daringfireball.net/projects/markdown/)-inspired formatting. No block elements, though.

| Input                          | Output                       |
| ---                            | ---                          |
| `**strong**` / `__strong__`    | __strong__                   |
| `*italic*` / `_italic_`        | _italic_                     |
| `~delete~`                     | ~~delete~~                   |
| `:"quote":`                    | <q>quote</q>                 |
| `@code@`                       | `code`                       |
| `[https://example.org]`        | https://example.org          |
| `[title](https://example.org)` | [title](https://example.org) |

## FAQ

**Are there any dependencies? Should I use Composer?**  
There are no dependencies. This is literally _one_ PHP file.

**Can I rename `index.php`?**  
You can, but the question is whether you should. (You shouldn’t.)

**How did this software come to be?**  
I forked [this script](https://github.com/lawl/b.php) and improved it for the worse.

**Uh… follow-up question: Is vicco secure?**  
It should be secure enough to run a crappy blog.

## Testimonials

Send me a PR if you want to add yours!

> I’m a huge fan of your blog, but I get the creeps from the PHP code.  
— [sternenseemann](https://github.com/sternenseemann)

> cute readme  
— [riotbib](https://github.com/riotbib)

## License

vicco is licensed under the [BSD-2-Clause license](https://opensource.org/licenses/BSD-2-Clause).
