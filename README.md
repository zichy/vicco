# ![vicco](https://cloud.githubusercontent.com/assets/173749/16890778/112997ea-4af2-11e6-910e-869ec77851fb.png)
__vicco__ is microblogging software in PHP. It’s named after [Vicco von Bülow](https://en.wikipedia.org/wiki/Vicco_von_B%C3%BClow).

## Features
* Blisteringly fast
* Web interface
* Text formatting
* Semantic HTML5 with microdata
* Atom feed

## Target audiences
* Hackers — It’s easy to customize, so you can do what you want.
* Hipsters — It’s crappy, so you can use it as a joke and feel ironic.

## Requirements
* A web server with PHP

## Installation
1. Change the config variables in `index.php`.
2. Upload it to your server and make the directory writable.
3. Open it in your browser.
4. It should work.

## Text formatting
There is some [Markdown](https://daringfireball.net/projects/markdown/)-inspired formatting. No block elements, though.

| Input | Output |
| --- | --- |
| `**strong**` or `__strong__` | __strong__ |
| `*italic*` or `_italic_` | _italic_ |
| `~delete~` | ~~delete~~ |
| `:"quote":` | <q>quote</q> |
| `@code@` | `code` |
| `[https://example.org]` / `[title](https://example.org)` | https://example.org / [title](https://example.org) |

## FAQ

**Are there any dependencies? Should I use Composer?**  
What the fuck? This is literally _one_ PHP file.

**Given that you released PHP software, you know how to write PHP, right?**  
Haha, no. Basically, I stole [this script](https://github.com/lawl/b.php) with (kind of) permission and made it worse.

**Uh … well, follow-up question: Is vicco secure?**  
Probably not.

## Testimonials
> I’m a huge fan of your blog, but I get the creeps from the PHP code.
— [sternenseemann](https://github.com/sternenseemann)

## To-do
* Security stuff
* Fix Atom feed
* Truncate posts for feed title/summary
* Fix navigation
