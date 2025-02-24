# vicco

vicco is brutalist blog software contained in a single PHP file.

## Features

* Responsive interface to publish, edit, and delete posts
* Text formatting
* Linkblog functionality
* Atom feed
* Open Graph & Microdata support

## Target audiences

* Hackers — It’s easy to customize.
* Hipsters — It’s crappy, you could use it as a joke.

## Requirements

* Web server with PHP
* SSL/TLS certificate

## Installation

1. Set your credentials in `index.php`.
2. Upload it to your server and make the directory writable.
3. Open the site in your browser.
4. Customise the configuration and submit the form.
5. Start blogging.

## Text formatting

There is some [Markdown](https://daringfireball.net/projects/markdown/)-inspired formatting.

| Input                          | Output                                          |
| ---                            | ---                                             |
| `**strong**` / `__strong__`    | __strong__                                      |
| `*italic*` / `_italic_`        | _italic_                                        |
| `~strikethrough~`              | ~~strikethrough~~                               |
| `>quotation block`             | <blockquote><p>quotation block</p></blockquote> |
| `:"inline quote":`             | <q>inline quote</q>                             |
| `@code@`                       | `code`                                          |
| `[https://example.org]`        | https://example.org                             |
| `[title](https://example.org)` | [title](https://example.org)                    |

## FAQ

**Are there any dependencies? Should I use Composer?**  
There are no dependencies. This is literally _one_ PHP file.

**One single file? How does this work?**  
Well, _actually_ several files are created in the folder `/vicco/`, which serves as some kind of rudimentary flat-file database. All posts are stored in JSON.

**Can I rename `index.php`?**  
You can, but the question is whether you should. (You shouldn’t.)

**How did vicco come to be?**  
I forked [this now ten-year-old script](https://github.com/lawl/b.php) and created a nearly complete rewrite.

**Is vicco secure?**  
I don’t know. It should be secure enough to run a crappy blog.

## Testimonials

> I’m a huge fan of your blog, but I get the creeps from the PHP code.  
— [sternenseemann](https://github.com/sternenseemann)

> cute readme  
— [riotbib](https://github.com/riotbib)

(Send me a PR if you want to add yours!)

## License

vicco is licensed under the [BSD-2-Clause license](https://opensource.org/licenses/BSD-2-Clause).
