<?php

/*
	SPDX-License-Identifier: BSD-2-Clause
	SPDX-FileCopyrightText: Copyright (c) 2015–2024 lawl(†), zichy
*/

class Config {
	static $username = 'admin';
	static $passphrase = 'CHANGEME';
	static $name = 'vicco';
	static $description = 'Yet another microblog'; // optional
	static $language = 'en'; // (ISO 639-1)
	static $dateFormat = 'd M Y, H:i';
	static $postsPerPage = 10;
	static $showLogin = true;
}

class Lang {
	static $author = 'Anonymous';
	static $search = 'Search';
	static $placeholder = 'Start typing &hellip;';
	static $postcontent = 'Post content';
	static $publish = 'Publish';
	static $save = 'Save';
	static $logout = 'Logout';
	static $edit = 'Edit';
	static $delete = 'Delete';
	static $deleteWarning = 'Do you really want to delete this post?';
	static $older = 'Older';
	static $newer = 'Newer';
	static $username = 'Username';
	static $passphrase = 'Passphrase';
	static $login = 'Login';
	static $back = 'Go back';
	static $error = 'Error';
	static $errorLogin = 'The credentials are incorrect.';
	static $errorEmpty = 'Your post must not be empty.';
	static $errorPostExists = 'A post with this ID already exists.';
	static $errorPostNonexistent = 'The post you wish to edit does not exist.';
	static $errorNoResults = 'No search results were found.';
	static $errorHacker = 'Nice try.';
}

class Sys {
	static $path = 'vicco/';
	static $key = 'key';
	static $value = 'value';
	static $db = 'db';
	static $css = 'style.css';
	static $js = 'script.js';
	static $postContent = 'postcontent';
	static $postDate = 'postdate';
	static $postDateTime = 'postdatetime';
	static $postId = 'postid';
	static $cookie = 'cookie';
}

session_start();

// Installation
if(get_kvp(Sys::$db, 'firstuse') === false) {
	if(!record_exists('')) {
		if(!mkdir(Sys::$path)) {
			die('No write permissions to create the folder "'.Sys::$path.'".');
		}
	}
	create_record(Sys::$db);
	create_index(Sys::$postDate, Sys::$postDate);

	set_file(null, Sys::$css, <<< 'EOD'
:root {
	--sans: system-ui, sans-serif;
	--mono: ui-monospace, monospace;
	--size: 1.6rem;
	--line: 1.5;
	--border: 2px solid #00f;
}
* {
	box-sizing: border-box;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-rendering: optimizeLegibility;
}
html {
	font-size: 62.5%;
	scroll-behavior: smooth;
}
*:focus-visible {
	outline: var(--border);
	outline-offset: 2px;
}
body {
	background-color: #fff;
	color: #000;
	font-size: var(--size);
	font-family: var(--mono);
	line-height: var(--line);
	max-width: 80ch;
	min-width: 375px;
	padding-inline: 2rem;
	margin: 4rem auto;
	overflow-x: hidden;
}
a {
	color: #00f;
}
a:is(:hover, :focus) {
	background-color: #fe9;
	box-decoration-break: clone;
}
h1 {
	font-size: 1.5em;
	line-height: 1;
}
h2 {
	font-size: 1em;
}
label {
	font-weight: bold;
	display: block;
	padding-block-end: 0.5rem;
}
:is(code, input, button) {
	font-size: var(--size);
}
code {
	background-color: #ccc;
	box-shadow: 0.25em 0 0 #ccc, -0.25em 0 0 #ccc;
}
input {
	background-color: #fff;
	font-family: var(--mono);
	width: 100%;
	height: 3.5rem;
	padding-inline: 1rem;
	border: var(--border);
	border-radius: 0.5rem;
}
input:focus {
	background-color: #fe9;
	outline: 0;
}
textarea {
	background-color: transparent;
	font-size: var(--size);
	font-family: var(--mono);
	line-height: var(--line);
	display: block;
	width: 100%;
	padding: 0 0 2rem 0;
	border: 0;
	resize: none;
}
textarea:focus {
	outline: none;
}
:is(button, .button) {
	background-color: #fff;
	color: #00f;
	font-size: 0.85em;
	font-family: var(--sans);
	font-weight: bold;
	line-height: 1;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	text-decoration: none;
	height: 3.5rem;
	padding-inline: 1rem;
	border: var(--border);
	border-radius: 0.5rem;
	cursor: pointer;
	touch-action: manipulation;
	user-select: none;
	-webkit-user-select: none;
}
:is(button, .button):hover {
	background-color: #fe9;
}
.row {
	display: flex;
	gap: 1.5rem;
}
.header {
	color: #00f;
	font-family: var(--sans);
	margin-block-end: 2rem;
}
@media (min-width: 80ch) {
	.header {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 2rem 4rem;
	}
}
.header :is(h1, p) {
	margin: 0;
}
.header a {
	text-decoration: none;
}
.search {
	display: flex;
	flex-shrink: 0;
}
@media (max-width: 80ch) {
	.search {
		margin-top: 1rem;
	}
}
.search input {
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}
.search button {
	margin-left: -2px;
	border-top-left-radius: 0;
	border-bottom-left-radius: 0;
}
.block > *:first-child {
	margin-block-start: 0;
}
.block > *:last-child {
	margin-block-end: 0;
}
.box {
	background-color: #eee;
	padding: 3rem 3rem 2rem;
	border-radius: 0.5rem;
	margin-block-end: 2rem;
}
@media (max-width: 80ch) {
	.box {
		padding: 2rem;
		margin-inline-start: -2rem;
		margin-inline-end: -2rem;
	}
}
.post-text {
	margin-block-end: 1.5em;
}
.post-meta {
	font-family: var(--sans);
	flex-direction: row;
	justify-content: space-between;
}
.permalink {
	color: #666;
	text-decoration: none;
	align-self: start;
}
@media (min-width: 80ch) {
	.site-meta {
		display: flex;
		justify-content: space-between;
	}
}
.login div {
	margin-block-end: 2rem;
}
.panel-meta {
	justify-content: end;
}
.footer {
	justify-content: space-between;
}
EOD
	);
	set_file(null, Sys::$js, <<< 'EOD'
const $textarea = document.getElementById('postcontent');
if($textarea) {
	function resizeArea($el) {
		let heightLimit = 500;
		$el.style.height = '';
		$el.style.height = Math.min($el.scrollHeight, heightLimit) + 'px';
	}
	resizeArea($textarea);
	$textarea.addEventListener('input', function(e){
		const $target = e.target || e.srcElement;
		resizeArea($target);
	});
}

const $adminForms = document.querySelectorAll('.admin');
if($adminForms) {
	$adminForms.forEach(($form) => {
		const warning = $form.dataset.warning;
		$form.addEventListener('submit', (e) => {
			if(confirm(warning)) {
				$form.submit();
			} else {
				e.preventDefault();
			}
		});
	});
}
EOD
	); set_kvp(Sys::$db, 'firstuse', 1);
}

// Database
function create_record($r) {
	$r = sanitize_key($r);
	if(!record_exists($r)) {
		mkdir(Sys::$path.$r);
	}
	return $r;
}

function set_file($r, $k, $v) {
	file_put_contents(Sys::$path.$r.'/'.$k,$v);
}

function set_kvp($r, $k, $v) {
	$f = Sys::$path.sanitize_key($r).'/'.sanitize_key($k);
	file_put_contents($f,$v);
	chmod($f, 0600);
}

function get_kvp($r, $k) {
	$p = Sys::$path.sanitize_key($r).'/'.sanitize_key($k);
	return file_exists($p) ? file_get_contents($p) : false;
}

function delete_kvp($r, $kvp) {
	unlink(Sys::$path.sanitize_key($r).'/'.sanitize_key($kvp));
}

function record_exists($p) {
	$p = sanitize_key($p);
	return file_exists(Sys::$path.$p) && is_dir(Sys::$path.$p);
}

function record_delete($r) {
	$r = sanitize_key($r);
	if(record_exists($r)) {
		$h = opendir(Sys::$path.$r);
		for($i = 0; ($e = readdir($h)) !== false; $i++) {
			if ($e != '.' && $e != '..' ) {
				unlink(Sys::$path.$r.'/'.$e);
			}
		}
		closedir($h);
		rmdir(Sys::$path.$r);
	}
}

function get_keys($r) {
	$s = scandir(Sys::$path.$r);
	return array_values(array_filter($s, function($v) {
		return $v != '.' && $v != '..';
	}));
}

function sanitize_key($k) {
	return preg_replace('/[^A-Za-z0-9_]/', '', $k);
}

function create_index($n, $k) {
	$d = array();
	$h = opendir(Sys::$path);
	for($i = 0; ($e = readdir($h)) !== false; $i++) {
		if ($e != '.' && $e != '..' && $e != Sys::$db) {
			$d[$i][Sys::$key] = $e;
			$d[$i][Sys::$value] = get_kvp($e, $k);
			if($d[$i][Sys::$value] === false) {
				array_pop($d);
			}
		}
	}
	closedir($h);
	set_kvp(Sys::$db, 'index_'.$n, serialize($d));
}

function get_index($n) {
	return unserialize(get_kvp(Sys::$db, 'index_'.$n));
}

// Status
function isLoggedin() {
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_COOKIE['vicco']) && $_COOKIE['vicco'] === tpl(Sys::$cookie)) {
		return true;
	}
}
function isEditing() {
	if (isset($_GET['edit'])) {
		return true;
	}
}

// Template function
function tpl() {
	$f = func_get_args();
	$n = sizeof($f) - 1;
	$t = get_kvp(Sys::$db, $f[0]);
	for($i = 1; $i < $n; $i += 2) {
		$t = str_replace('{{'.$f[$i].'}}', $f[$i + 1], $t);
	}
	return $t;
}

// Footer template
function footer($results = 0) { ?>
	</main>
	<footer class="footer row">
		<?php if(!isset($_GET['p']) && !isEditing() && $results >= Config::$postsPerPage) { ?>
			<nav class="row">
				<?php if (@$_GET['skip'] > 0): ?>
					<a href="?skip=<?= (@$_GET['skip'] > 0 ? @$_GET['skip'] - Config::$postsPerPage : 0).'&amp;s='.@urlencode($_GET['s']) ?>" class="button">&larr; <?= Lang::$newer ?></a>
				<?php endif ?>
				<?php if (@$_GET['skip'] + Config::$postsPerPage < $results): ?>
					<a href="?skip=<?= (@$_GET['skip'] + Config::$postsPerPage < $results ? @$_GET['skip'] + Config::$postsPerPage : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><?= Lang::$older ?> &rarr;</a>
				<?php endif ?>
			</nav>
		<?php } ?>

		<?php if(Config::$showLogin && !isset($_GET['login']) && !isLoggedin()): ?>
			<a class="button" href="?login">Login</a>
		<?php elseif(isLoggedin()): ?>
			<form action="/" method="post">
				<button type="submit" name="logout"><?= Lang::$logout ?></button>
			</form>
		<?php endif ?>
	</footer>

	<?php if (isLoggedin()): ?>
		<script src="<?= Sys::$path.Sys::$js ?>"></script>
	<?php endif ?>
	</body></html>
<?php }

// Error template
function error($text) { ?>
	<section class="box block">
		<h2><?= Lang::$error ?></h2>
		<p><?= $text ?>
		<p><a class="button" href="/"><?= Lang::$back ?></a>
	</section>
<?php
	footer();
	die();
}

// Go to index
function rmain() {
	header('Location: /');
	die();
}

// Text formatting
function parse($t) {
	$t = preg_replace('/(\*\*|__)(.*?)\1/', '<strong>\2</strong>', $t);
	$t = preg_replace('/(\*|_)(.*?)\1/', '<em>\2</em>', $t);
	$t = preg_replace('/\~(.*?)\~/', '<del>\1</del>', $t);
	$t = preg_replace('/\:\"(.*?)\"\:/', '<q>\1</q>', $t);
	$t = preg_replace('/\@(.*?)\@/', '<code>\1</code>', $t);
	$t = preg_replace('/\[([^\[]+)\]\(([^\)]+)\)/', '<a href=\'\2\'>\1</a>', $t);
	$t = preg_replace('/\[(.*?)\]/', '<a href=\'\1\'>\1</a>', $t);
	$t = '<p>' . $t . '</p>';
	$t = str_replace("\r\n\r\n", "</p><p>", $t);
	$t = str_replace("\n\n", "</p><p>", $t);
	$t = str_replace("\r\n", "<br>", $t);
	$t = str_replace("\n", "<br>", $t);
	return $t;
}

// Feed
if(isset($_GET['feed'])) {
	$p = @array_slice(get_index(Sys::$postDate), 0, Config::$postsPerPage);
	$u = 'https://$_SERVER[HTTP_HOST]';
	$f = 'https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]';
	header('Content-type: application/atom+xml'); ?>
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title><?= Config::$name ?></title>
<?php if (!empty(Config::$description)): ?>
<subtitle><?= Config::$description ?></subtitle>
<author>
	<name><?= Lang::$author ?></name>
</author>
<?php endif ?>
<link href="<?= $u ?>" />
<link href="<?= $f ?>" rel="self"/>
<?php foreach($p as $m): ?>
<entry>
	<title><?= date(Config::$dateFormat, $m[Sys::$value]) ?></title>
	<link href="<?= $u . '?p='.$m[Sys::$key] ?>" />
	<content type="html"><![CDATA[<?= parse(get_kvp($m[Sys::$key], Sys::$postContent)) ?>]]></content>
	<updated><?= date('Y-m-d\TH:i:sP', $m[Sys::$value]) ?></updated>
	<id>urn:uuid:<?= $m[Sys::$key] ?></id>
</entry>
<?php endforeach ?>
</feed><?php die();
}

// Header
?>
<!DOCTYPE html><html lang="<?= Config::$language ?>"><head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if (!empty(Config::$description)): ?>
		<meta name="description" content="<?= Config::$description ?>">
	<?php endif ?>

	<title><?= Config::$name ?></title>

	<link href="/?feed" type="application/atom+xml" title="<?= Config::$name ?> feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="<?= Sys::$path.Sys::$css ?>" media="screen">

</head>

<body itemscope itemtype="http://schema.org/Blog">

<header class="header">
	<div>
		<h1 itemprop="name">
		<?php if (!empty($_GET)): ?>
			<a href="/"><?= Config::$name ?></a>
		<?php else: ?>
			<?= Config::$name ?>
		<?php endif ?>
		</h1>
	<?php if (!empty(Config::$description)): ?>
		<p itemprop="description"><?= Config::$description ?></p>
	<?php endif ?>
	</div>

	<form class="search" action="/" method="get" role="search">
		<input type="text" name="s" aria-label="<?= Lang::$search ?>">
		<button type="submit"><?= Lang::$search ?></button>
	</form>
</header>

<main>
<?php

// Cookie
function set_cookie() {
	$identifier = bin2hex(random_bytes('64'));
	set_kvp(Sys::$db, Sys::$cookie, $identifier);
	setcookie('vicco', $identifier, time()+(3600*24*30));
}
function delete_cookie() {
	delete_kvp(Sys::$db, Sys::$cookie);
	setcookie('vicco', '', time()-(3600*24*30));
}

// Login
if(isset($_GET['login'])) {
	if(isLoggedin()) {
		rmain();
	} else { ?>
		<form class="box login" action="/" method="post">
			<div>
				<label for="username"><?= Lang::$username ?></label>
				<input type="text" id="username" name="username" autocomplete="username">
			</div>
			<div>
				<label for="passphrase"><?= Lang::$passphrase ?></label>
				<input type="password" id="passphrase" name="passphrase" autocomplete="current-password">
			</div>
			<button type="submit" name="login"><?= Lang::$login ?></button>
		</form>
	<?php
		footer();
		die();
	}
}
if(isset($_POST['login'])) {
	if(hash_equals(Config::$username, $_POST['username']) && hash_equals(Config::$passphrase, $_POST['passphrase'])) { 
		$_SESSION['loggedin'] = true;
		set_cookie();
		rmain();
	} else {
		error(Lang::$errorLogin);
	}
}
if(isLoggedin()) {
	// Submit posts
	if(isset($_POST['submit'])) {
		$r = 0;
		if(empty($_POST[Sys::$postContent])) {
			error(Lang::$errorEmpty);
		}
		if(empty($_POST[Sys::$postId])) {
			$r = create_record(uniqid());
			set_kvp($r, Sys::$postDate, time());
		} else {
			if(!record_exists($_POST[Sys::$postId])) {
				error(Lang::$errorPostExists);
			}
			$r = $_POST[Sys::$postId];
		}
		set_kvp($r, Sys::$postContent, $_POST[Sys::$postContent]);
		create_index(Sys::$postDate, Sys::$postDate);
	}

	// Delete posts
	if(isset($_POST['delete'])) {
		record_delete($_POST['postid']);
		create_index(Sys::$postDate, Sys::$postDate);
	} 

	if (isEditing() && !record_exists($_GET['edit'])) {
		error(Lang::$errorPostNonexistent);
	}

	if (!isset($_GET['p'])): ?>
		<form class="box panel" action="/" method="post">
			<input type="hidden" name="postid" id="postid" value="<?= (isEditing() ? $_GET['edit'] : '') ?>">
			<textarea id="postcontent" name="postcontent" placeholder="<?= Lang::$placeholder ?>" aria-label="<?= Lang::$postcontent ?>" spellcheck="false" rows="1" autofocus><?= (isEditing() ? get_kvp($_GET['edit'], Sys::$postContent) : '') ?></textarea>

			<div class="panel-meta row">
				<button type="submit" id="submit" name="submit"><?= (isEditing() ? Lang::$save : Lang::$publish) ?></button>
			</div>
		</form>
	<?php endif;

} elseif(isset($_POST['submit']) || isset($_POST['delete']) || isEditing()) {
	error(Lang::$errorHacker);
}

// Logout
if(isset($_POST['logout'])) {
	session_destroy();
	delete_cookie();
	rmain();
}

// Posts
$p = get_index(Sys::$postDate);

// Search
if(!empty($_GET['s'])) {
	$s = explode(' ',$_GET['s']);
	foreach($p as $k => $m) {
		$c = strtolower(parse(get_kvp($m[Sys::$key], Sys::$postContent)));
		$f = true;
		for($i = 0; $i < sizeof($s); $i++) {
			if(strpos($c, strtolower($s[$i])) === false) {
				$f = false;
				break;
			}
		}
		if(!$f) {
			unset($p[$k]);
		}
	}
}
$results = sizeof($p);
if($results == 0) {
	error(Lang::$errorNoResults);
}

// Sorting
uasort($p, function($a, $b) {
	if($a[Sys::$value] == $b[Sys::$value]) {
		return 0;
	} else {
		return $b[Sys::$value] <=> $a[Sys::$value];
	}
});

// Get posts
if(isset($_GET['p']) && record_exists($_GET['p'])) {
	$p = array(array(Sys::$value => get_kvp($_GET['p'], Sys::$postDate), Sys::$key => $_GET['p']));
}
$p = @array_slice($p, $_GET['skip'], Config::$postsPerPage);

// Posts
if(!isEditing()) {
	foreach($p as $m): ?>
		<article class="box post" itemscope itemtype="https://schema.org/BlogPosting">
			<div class="post-text block" itemprop="articleBody"><?= parse(get_kvp($m[Sys::$key], Sys::$postContent)) ?></div>
			<footer class="post-meta row">
				<a class="permalink" href="?p=<?= $m[Sys::$key] ?>" itemprop="url">
					<time datetime="<?= date('Y-m-d H:i:s', $m[Sys::$value]) ?>" itemprop="datePublished" pubdate><?= date(Config::$dateFormat, $m[Sys::$value]) ?></time>
				</a>
			<?php if (isLoggedin()): ?>
				<form class="admin row" action="/" method="post" data-warning="<?= Lang::$deleteWarning ?>">
					<input type="hidden" name="postid" value="<?= $m[Sys::$key] ?>">
					<a class="button" href="?edit=<?= $m[Sys::$key] ?>"><?= Lang::$edit ?></a>
					<button type="submit" class="delete" name="delete"><?= Lang::$delete ?></button>
				</form>
			<?php endif ?>
			</footer>
		</article>
	<?php endforeach;
}

// Footer
footer($results);

?>
