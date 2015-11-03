## ![vicco](https://cloud.githubusercontent.com/assets/3044987/10921003/8366befc-8273-11e5-9ca3-68b0c12b7b4f.png)
_vicco_ is microblogging software in PHP. It’s named after [Vicco von Bülow](https://en.wikipedia.org/wiki/Vicco_von_B%C3%BClow).

### Features
* Blisteringly fast
* Atom feed (broken)
* Web interface
* Markdown
* Human readable source code (kind of)
* No JavaScript (yes, this is a feature)
* No comments (yes, this is also a feature)

### Target audiences
* Hackers — It’s easy to customize, so you can do what you want.
* Hipsters — It’s crappy, so you can use it as a joke and feel ironic.

### Requirements
* A web server with PHP

### Installation
1. Change the config variables in `index.php`.
2. Upload it to your server and make the directory writable.
3. Open it in your browser.
4. It should work.

### Markdown
There are some [Markdown](https://daringfireball.net/projects/markdown/) inspired tags:

* `**strong**` or `__strong__`
* `*italic*` or `_italic_`
* `~delete~`
* `:"quote":`
* `@code@`
* `[https://example.org]` or `[title](https://example.org)`

No block elements, though.

### FAQ

**Are there any dependencies? Should I use Composer?**  
What the fuck? This is literally _one_ PHP file.

**Given that you released PHP software, you know how to write PHP, right?**  
Haha, no.

**Uh … well, follow-up question: Is _vicco_ secure?**  
Let me say it this way: Do only use this software publicly if your visitors assure that they won’t hack it and you believe them. Otherwise, don’t.

### To-do
* Make it secure
* Valid Atom feed
* Truncate posts for feed title/summary
* Decline empty posts
* Other stuff
