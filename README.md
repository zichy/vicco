# vicco

__vicco__ is minimalist microblogging software contained in a single PHP file.

## Features

* Horrendous code
* Responsive layout
* Web interface to publish and edit posts
* Text formatting
* Atom feed (broken)

## Target audiences

* Hackers — It’s easy to customize.
* Hipsters — It’s crappy, you could use it as a joke.

## Requirements

* Web server with PHP
* SSL/TLS certificate

## Installation

1. Adjust the config variables in `index.php`. Remember to change your credentials.
2. Upload it to your server and make the directory writable.
3. Open the site in your browser.
4. It should work.

You can login with your credentials at `/?login`.

## Text formatting

There is some [Markdown](https://daringfireball.net/projects/markdown/)-inspired formatting. No block elements, though.

| Input                                                    | Output                                             |
| ---                                                      | ---                                                |
| `**strong**` / `__strong__`                              | __strong__                                         |
| `*italic*` / `_italic_`                                  | _italic_                                           |
| `~delete~`                                               | ~~delete~~                                         |
| `:"quote":`                                              | <q>quote</q>                                       |
| `@code@`                                                 | `code`                                             |
| `[https://example.org]` / `[title](https://example.org)` | https://example.org / [title](https://example.org) |

## FAQ

**Are there any dependencies? Should I use Composer?**  
There are no dependencies. This is literally _one_ PHP file.

**Since you published PHP software, you know how to write PHP, right?**  
Haha, no. Basically, I stole [this script](https://github.com/lawl/b.php) (with permission) and improved it for the worse.

**Uh… follow-up question: Is vicco secure?**  
It should be secure enough to run a crappy blog.

## Testimonials

> I’m a huge fan of your blog, but I get the creeps from the PHP code.  
— [sternenseemann](https://github.com/sternenseemann)

> cute readme  
— [riotbib](https://github.com/riotbib)

## To-do

* Fix Atom feed

## License

vicco is licensed under the [BSD-2-Clause license](https://opensource.org/licenses/BSD-2-Clause).
