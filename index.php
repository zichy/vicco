<?php

/*
	SPDX-License-Identifier: BSD-2-Clause
	SPDX-FileCopyrightText: Copyright (c) 2015–2024 lawl(†), zichy
*/

class Config {
	static $blogName = 'vicco';
	static $blogDesc = 'Yet another microblog'; // optional
	static $username = 'admin';
	static $passphrase = 'CHANGEME';
	static $language = 'en'; // (ISO 639-1)
	static $bgColor = '#fff';
	static $textColor = '#00f';
	static $accentColor = '#fe9';
	static $dateFormat = 'd M Y, H:i';
	static $postsPerPage = 10;
	static $postsFeed = 20;
	static $showLogin = true;
}

class Lang {
	static $author = 'Anonymous';
	static $search = 'Search';
	static $placeholder = 'Start writing &hellip;';
	static $content = 'Post content';
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
	static $errorNoResults = 'No posts were found.';
	static $errorHacker = 'Nice try.';
}

class Sys {
	static $path = 'vicco/';
	static $db = 'db';
	static $css = 'style.css';
	static $js = 'script.js';
}

session_start();

// Installation
if(get_kvp(Sys::$db, 'firstuse') === false) {
	if(!record_exists('')) {
		if(!mkdir(Sys::$path)) {
			die('No write permissions to create the folder ' . Sys::$path);
		}
	}
	create_record(Sys::$db);
	create_index('date', 'date');

	set_file(null, Sys::$css, <<< 'EOD'
:root {
	--sans: system-ui, sans-serif;
	--mono: ui-monospace, monospace;
	--size: 1.6rem;
	--line: 1.5;
	--border: 1px solid var(--text);
}
* {
	box-sizing: border-box;
	-webkit-font-smoothing: antialiased;
	text-rendering: optimizeLegibility;
}
html {
	font-size: 62.5%;
	scroll-behavior: smooth;
}
::selection {
	background-color: var(--accent);
}
*:focus-visible {
	outline: var(--border);
	outline-offset: 2px;
}
body {
	background-color: var(--background);
	color: var(--text);
	font-size: var(--size);
	font-family: var(--sans);
	line-height: var(--line);
	max-width: 1024px;
	min-width: 375px;
	padding-inline: 2rem;
	margin: 4rem auto;
	overflow-x: hidden;
}
a {
	color: var(--text);
}
a:is(:hover, :focus-visible) {
	background-color: var(--accent);
}
:is(h1, h2) {
	margin: 0;
}
h1 {
	font-size: 1.25em;
	font-style: italic;
	line-height: 1;
	margin-block: 0;
}
h2 {
	font-size: 1em;
}
h2 + p {
	margin-block-start: 0;
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
	background-color: var(--accent);
	box-shadow: 0.25em 0 0 var(--accent), -0.25em 0 0 var(--accent);
}
:is(input, textarea) {
	background-color: transparent;
	color: var(--text);
	font-family: var(--mono);
	font-size: var(--size);
}
:is(input, textarea):focus-visible {
	outline: none;
}
input {
	width: 100%;
	height: 3.5rem;
	padding-inline: 1rem;
	border: var(--border);
	border-radius: 0.5rem;
}
input:is(:hover, :focus) {
	background-color: var(--accent);
}
textarea {
	line-height: var(--line);
	display: block;
	width: 100%;
	padding: 0;
	border: 0;
	resize: none;
}
:is(button, .button) {
	background-color: inherit;
	color: inherit;
	font-size: 0.85em;
	font-family: var(--sans);
	font-weight: bold;
	text-decoration: none;
	line-height: 1;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 0.5rem 1rem;
	border: var(--border);
	border-radius: 0.5rem;
	cursor: pointer;
	touch-action: manipulation;
	user-select: none;
	-webkit-user-select: none;
}
:is(button, .button):is(:hover, :focus-visible) {
	background-color: var(--text);
	color: var(--background);
}
.row {
	display: flex;
	gap: 1.5rem;
}
header {
	font-family: var(--sans);
	display: flex;
	gap: 2rem 4rem;
	padding-block-end: 2rem;
	border-bottom: var(--border);
}
@media (max-width: 768px) {
	header {
		flex-direction: column;
	}
}
@media (min-width: 769px) {
	header {
		justify-content: space-between;
		align-items: flex-end;
	}
}
header p {
	margin: 0;
}
header a {
	text-decoration: none;
}
.search {
	display: flex;
	flex-shrink: 0;
}
.search input {
	border-right: 0;
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}
.search button {
	border-top-left-radius: 0;
	border-bottom-left-radius: 0;
}
.text > *:first-child {
	margin-block-start: 0;
}
.text > *:last-child {
	margin-block-end: 0;
}
.grid {
	display: grid;
	grid-gap: 2rem 3rem;
	padding-block: 2rem;
}
@media (max-width: 768px) {
	.grid {
		grid-template-rows: auto;
	}
}
@media (min-width: 769px) {
	.grid {
		grid-template-columns: 1fr 16rem;
	}
}
.post:not(:last-child) {
	border-bottom: var(--border);
}
.post-text {
	font-family: var(--mono);
}
.post-meta {
	display: flex;
	flex-direction: column;
	row-gap: 1rem;
}
.permalink {
	text-decoration: none;
	align-self: start;
}
time {
	font-weight: bold;
}
.box {
	padding-block: 2rem;
}
.login div {
	margin-block-end: 2rem;
}
.panel:not(:last-child) {
	border-bottom: var(--border);
}
.panel-meta {
	justify-content: end;
}
.footer {
	display: grid;
	grid-template-columns: 1fr auto;
	grid-template-areas: 'nav acc';
	grid-column-gap: 4rem;
	padding-block-start: 2rem;
	border-top: var(--border);
}
nav {
	grid-area: nav;
}
.acc {
	grid-area: acc;
}
EOD
	);
	set_file(null, Sys::$js, <<< 'EOD'
if (window.history.replaceState) {
	window.history.replaceState(null, null, window.location.href);
}

const $textarea = document.getElementById('content');
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
	file_put_contents(Sys::$path . $r . '/' . $k, $v);
}

function set_kvp($r, $k, $v) {
	$f = Sys::$path.sanitize_key($r) . '/' . sanitize_key($k);
	file_put_contents($f, $v);
	chmod($f, 0600);
}

function get_kvp($r, $k) {
	$p = Sys::$path.sanitize_key($r) . '/' . sanitize_key($k);
	return file_exists($p) ? file_get_contents($p) : false;
}

function delete_kvp($r, $kvp) {
	unlink(Sys::$path.sanitize_key($r) . '/' . sanitize_key($kvp));
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
				unlink(Sys::$path . $r . '/' . $e);
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
			$d[$i]['key'] = $e;
			$d[$i]['value'] = get_kvp($e, $k);
			if($d[$i]['value'] === false) {
				array_pop($d);
			}
		}
	}
	closedir($h);
	set_kvp(Sys::$db, 'index_' . $n, serialize($d));
}

function get_index($n) {
	return unserialize(get_kvp(Sys::$db, 'index_' . $n));
}

// Status
function isLoggedin() {
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_COOKIE['vicco']) && $_COOKIE['vicco'] === db('cookie')) {
		return true;
	}
}
function isEditing() {
	if (isset($_GET['edit'])) {
		return true;
	}
}
function isSearching() {
	if (isset($_GET['s'])) {
		return true;
	}
}

// Check database
function db() {
	$f = func_get_args();
	$n = sizeof($f) - 1;
	$t = get_kvp(Sys::$db, $f[0]);
	for($i = 1; $i < $n; $i += 2) {
		$t = str_replace('{{' . $f[$i] . '}}', $f[$i + 1], $t);
	}
	return $t;
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
	$p = @array_slice(get_index('date'), 0, Config::$postsFeed);
	$u = 'https://' . $_SERVER['HTTP_HOST'];
	$f = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('Content-type: application/atom+xml'); ?>
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title><?= Config::$blogName ?></title>
<?php if (!empty(Config::$blogDesc)): ?>
<subtitle><?= Config::$blogDesc ?></subtitle>
<author>
	<name><?= Lang::$author ?></name>
</author>
<?php endif ?>
<link href="<?= $u ?>" />
<link href="<?= $f ?>" rel="self"/>
<?php foreach($p as $m): ?>
<entry>
	<title><?= date(Config::$dateFormat, $m['value']) ?></title>
	<link href="<?= $u . '?p=' . $m['key'] ?>" />
	<content type="html"><![CDATA[<?= parse(get_kvp($m['key'], 'content')) ?>]]></content>
	<updated><?= date('Y-m-d\TH:i:sP', $m['value']) ?></updated>
	<id>urn:uuid:<?= $m['key'] ?></id>
</entry>
<?php endforeach ?>
</feed><?php die();
}

// Header
?>
<!DOCTYPE html><html lang="<?= Config::$language ?>"><head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if (!empty(Config::$blogDesc)): ?>
		<meta name="description" content="<?= Config::$blogDesc ?>">
	<?php endif ?>

	<title><?= Config::$blogName ?></title>

	<link href="/?feed" type="application/atom+xml" title="<?= Config::$blogName ?> feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="<?= Sys::$path.Sys::$css ?>" media="screen">
	<style>:root { --background: <?= Config::$bgColor ?>; --text: <?= Config::$textColor ?>; --accent: <?= Config::$accentColor ?>; }</style>

</head><body itemscope itemtype="http://schema.org/Blog">

<header>
	<div>
		<h1 itemprop="name">
		<?php if (!empty($_GET)): ?>
			<a href="/"><?= Config::$blogName ?></a>
		<?php else: ?>
			<?= Config::$blogName ?>
		<?php endif ?>
		</h1>
	<?php if (!empty(Config::$blogDesc)): ?>
		<p itemprop="description"><?= Config::$blogDesc ?></p>
	<?php endif ?>
	</div>

	<form class="search" action="/" method="get" role="search">
		<input type="search" name="s" aria-label="<?= Lang::$search ?>" required>
		<button type="submit"><?= Lang::$search ?></button>
	</form>
</header><main>
<?php

// Footer template
function footer($results = 0) { ?>
	</main><footer class="footer">
		<?php if(!isset($_GET['p']) && !isEditing() && $results >= Config::$postsPerPage) { ?>
			<nav class="row">
				<?php if (@$_GET['skip'] > 0): ?>
					<a href="?skip=<?= (@$_GET['skip'] > 0 ? @$_GET['skip'] - Config::$postsPerPage : 0) . '&amp;s=' . @urlencode($_GET['s']) ?>" class="button">&larr; <?= Lang::$newer ?></a>
				<?php endif ?>
				<?php if (@$_GET['skip'] + Config::$postsPerPage < $results): ?>
					<a href="?skip=<?= (@$_GET['skip'] + Config::$postsPerPage < $results ? @$_GET['skip'] + Config::$postsPerPage : @(int)$_GET['skip']) . '&amp;s=' . @urlencode($_GET['s']) ?>" class="button"><?= Lang::$older ?> &rarr;</a>
				<?php endif ?>
			</nav>
		<?php } ?>

		<div class="acc">
			<?php if(Config::$showLogin && !isset($_GET['login']) && !isLoggedin()): ?>
				<a class="button" href="?login">Login</a>
			<?php elseif(isLoggedin()): ?>
				<form action="/" method="post">
					<button type="submit" name="logout"><?= Lang::$logout ?></button>
				</form>
			<?php endif ?>
		</div>
	</footer>

	<?php if (isLoggedin()): ?>
		<script src="<?= Sys::$path.Sys::$js ?>"></script>
	<?php endif ?>
	</body></html>
<?php }

// Error template
function error($text) { ?>
	<section class="box text">
		<h2><?= Lang::$error ?></h2>
		<p><?= $text ?>
		<p><a class="button" href="/"><?= Lang::$back ?></a>
	</section>
<?php
	footer();
	die();
}

// Cookie
function set_cookie() {
	$identifier = bin2hex(random_bytes('64'));
	set_kvp(Sys::$db, 'cookie', $identifier);
	setcookie('vicco', $identifier, time()+(3600*24*30));
}
function delete_cookie() {
	delete_kvp(Sys::$db, 'cookie');
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
		if(empty($_POST['content'])) {
			error(Lang::$errorEmpty);
		}
		if(empty($_POST['id'])) {
			$r = create_record(uniqid());
			set_kvp($r, 'date', time());
		} else {
			if(!record_exists($_POST['id'])) {
				error(Lang::$errorPostExists);
			}
			$r = $_POST['id'];
		}
		set_kvp($r, 'content', $_POST['content']);
		create_index('date', 'date');
	}

	// Delete posts
	if(isset($_POST['delete'])) {
		record_delete($_POST['id']);
		create_index('date', 'date');
	} 

	if (isEditing() && !record_exists($_GET['edit'])) {
		error(Lang::$errorPostNonexistent);
	}

	if ((!(isset($_GET['p'])) && !isSearching())): ?>
		<form class="panel grid" action="/" method="post">
			<textarea id="content" name="content" placeholder="<?= Lang::$placeholder ?>" aria-label="<?= Lang::$content ?>" spellcheck="false" rows="1" autofocus required><?= (isEditing() ? get_kvp($_GET['edit'], 'content') : '') ?></textarea>

			<div class="panel-meta">
				<input type="hidden" name="id" value="<?= (isEditing() ? $_GET['edit'] : '') ?>">
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
$p = get_index('date');

// Search
if(!empty($_GET['s'])) {
	$s = explode(' ', $_GET['s']);
	foreach($p as $k => $m) {
		$c = strtolower(parse(get_kvp($m['key'], 'content')));
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
if(($results == 0) && isSearching()) {
	error(Lang::$errorNoResults);
}

// Sorting
uasort($p, function($a, $b) {
	if($a['value'] == $b['value']) {
		return 0;
	} else {
		return $b['value'] <=> $a['value'];
	}
});

// Get posts
if(isset($_GET['p']) && record_exists($_GET['p'])) {
	$p = array(array('value' => get_kvp($_GET['p'], 'date'), 'key' => $_GET['p']));
}
$p = @array_slice($p, $_GET['skip'], Config::$postsPerPage);

// Posts
if(!isEditing()) {
	foreach($p as $m): ?>
		<article class="post grid" itemscope itemtype="https://schema.org/BlogPosting">
			<div class="post-text text" itemprop="articleBody"><?= parse(get_kvp($m['key'], 'content')) ?></div>
			<footer class="post-meta">
				<?php $time = "<time datetime=\"".date('Y-m-d H:i:s', $m['value'])."\" itemprop=\"datePublished\" pubdate>".date(Config::$dateFormat, $m['value'])."</time>" ?>
				<?php if (!isset($_GET['p'])): ?>
					<a class="permalink" href="?p=<?= $m['key'] ?>" itemprop="url">
						<?= $time ?>
					</a>
				<?php else: ?>
					<?= $time ?>
				<?php endif ?>
				<?php if (isLoggedin()): ?>
					<form class="admin row" action="/" method="post" data-warning="<?= Lang::$deleteWarning ?>">
						<input type="hidden" name="id" value="<?= $m['key'] ?>">
						<a class="button" href="?edit=<?= $m['key'] ?>"><?= Lang::$edit ?></a>
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
