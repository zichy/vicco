<?php

/*
	SPDX-License-Identifier: BSD-2-Clause
	SPDX-FileCopyrightText: Copyright (c) 2015–2023 lawl(†), zichy
*/

// Site name
const SITENAME = 'vicco';

// Description (optional)
const SITEDESC = 'Yet another microblog';

// Username
const USERNAME = 'admin';

// Passphrase
const PASSPHRASE = 'CHANGEME';

// Date format
const DATEFORMAT = 'd M Y H:i:s';

// Posts per page
const POSTSPERPAGE = 10;

// Language (ISO 639-1)
const SITELANG = 'en';

// Language strings
const L_SEARCH = 'Search';
const L_PLACEHOLDER = 'Start typing &hellip;';
const L_POSTCONTENT = 'Post content';
const L_PUBLISH = 'Publish';
const L_LOGOUT = 'Logout';
const L_EDIT = 'Edit';
const L_DELETE = 'Delete';
const L_OLDER = 'Older';
const L_NEWER = 'Newer';
const L_USERNAME = 'Username';
const L_PASSPHRASE = 'Passphrase';
const L_LOGIN = 'Login';
const L_BACK = 'Go back';
const L_ERROR = 'Error';
const L_ERROR_LOGIN = 'The credentials are incorrect.';
const L_ERROR_EMPTY = 'Your post must contain text.';
const L_ERROR_POSTEXISTS = 'A post with this ID already exists.';
const L_ERROR_POSTNONEXISTENT = 'The post you wish to edit does not exist.';
const L_ERROR_NORESULTS = 'No search results were found.';
const L_ERROR_HACKER = 'Nice try.';

// System constants (Do not change)
const DATAPATH = 'vicco/';
const KEY = 'key';
const VALUE = 'value';
const DB = 'db';
const CSS = 'style.css';
const JS = 'script.js';
const D_POSTCONTENT = 'postcontent';
const D_POSTDATE = 'postdate';
const D_POSTDATETIME = 'postdatetime';
const D_POSTID = 'postid';
const COOKIE = 'cookie';

session_start();

// Installation
if(get_kvp(DB, 'firstuse') === false) {
	if(!record_exists('')) {
		if(!mkdir(DATAPATH)) {
			die('No write permissions to create the folder "'.DATAPATH.'".');
		}
	}
	create_record(DB);
	create_index(D_POSTDATE, D_POSTDATE);

	set_file(null, CSS, <<< 'EOD'
:root {
	--f-size: 1.6rem;
	--f-line: 1.5;
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
*:focus {
	outline: 1px solid currentColor;
	outline-offset: 1px;
}
body {
	background-color: #eee;
	color: #000;
	font-family: var(--f-sans);
	font-size: var(--f-size);
	line-height: var(--f-line);
	max-width: 80ch;
	min-width: 375px;
	padding-inline: 2rem;
	margin: 4rem auto;
	overflow-x: hidden;
}
a {
	color: #00c;
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
time {
	font-style: italic;
}
label {
	font-weight: bold;
	display: block;
	padding-block-end: 0.5rem;
}
:is(code, input, button) {
	font-size: var(--f-size);
}
code {
	background-color: #eee;
}
input {
	background-color: #fff;
	font-family: var(--f-sans);
	width: 100%;
	height: 3.5rem;
	padding-inline: 1rem;
	border: 1px solid #888;
}
input:focus {
	background-color: #fe9;
	outline: 0;
}
textarea {
	background-color: transparent;
	font-size: var(--f-size);
	font-family: var(--f-family);
	line-height: var(--f-line);
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
	color: currentColor;
	font-size: 0.85em;
	font-family: sans-serif;
	font-weight: bold;
	line-height: 1;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	text-decoration: none;
	height: 3.5rem;
	padding-inline: 1rem;
	margin: 0;
	border: 1px solid #888;
	cursor: pointer;
	touch-action: manipulation;
	user-select: none;
	-webkit-user-select: none;
}
:is(button, .button):hover {
	background-color: #fff;
	text-decoration: underline;
}
header {
	margin-block-end: 2rem;
}
@media (min-width: 80ch) {
	header {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 2rem 4rem;
	}
}
header :where(h1, p) {
	margin: 0;
}
header a {
	color: var(--c-text);
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
.search button {
	margin-left: -1px;
}
.box {
	background-color: #fff;
	padding: 3rem 3rem 2rem;
	margin-block-end: 2rem;
}
@media (max-width: 80ch) {
	.box {
		padding: 2rem;
		margin-inline-start: -2rem;
		margin-inline-end: -2rem;
	}
}
.box > *:first-child {
	margin-block-start: 0;
}
.post-text {
	margin-block-end: 1.5em;
}
.post-meta {
	flex-direction: row;
	justify-content: space-between;
}
.post-meta a {
	text-decoration: none;
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
.row {
	display: flex;
	gap: 1.5rem;
}
nav {
	justify-content: center;
}

EOD
	);
	set_file(null, JS, <<< 'EOD'
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
		$form.addEventListener('submit', (e) => {
			if(confirm()) {
				$form.submit();
			} else {
				e.preventDefault();
			}
		});
	});
}

EOD
	); set_kvp(DB, 'firstuse', 1);
}

// Database
function create_record($r) {
	$r = sanitize_key($r);
	if(!record_exists($r)) {
		mkdir(DATAPATH.$r);
	}
	return $r;
}

function set_file($r, $k, $v) {
	file_put_contents(DATAPATH.$r.'/'.$k,$v);
}

function set_kvp($r, $k, $v) {
	file_put_contents(DATAPATH.sanitize_key($r).'/'.sanitize_key($k),$v);
}

function get_kvp($r, $k) {
	$p = DATAPATH.sanitize_key($r).'/'.sanitize_key($k);
	return file_exists($p) ? file_get_contents($p) : false;
}

function delete_kvp($r, $kvp) {
	unlink(DATAPATH.sanitize_key($r).'/'.sanitize_key($kvp));
}

function record_exists($p) {
	$p = sanitize_key($p);
	return file_exists(DATAPATH.$p) && is_dir(DATAPATH.$p);
}

function record_delete($r) {
	$r = sanitize_key($r);
	if(record_exists($r)) {
		$h = opendir(DATAPATH.$r);
		for($i = 0; ($e = readdir($h)) !== false; $i++) {
			if ($e != '.' && $e != '..' ) {
				unlink(DATAPATH.$r.'/'.$e);
			}
		}
		closedir($h);
		rmdir(DATAPATH.$r);
	}
}

function get_keys($r) {
	$s = scandir(DATAPATH.$r);
	return array_values(array_filter($s, function($v) {
		return $v != '.' && $v != '..';
	}));
}

function sanitize_key($k) {
	return preg_replace('/[^A-Za-z0-9_]/', '', $k);
}

function create_index($n, $k) {
	$d = array();
	$h = opendir(DATAPATH);
	for($i = 0; ($e = readdir($h)) !== false; $i++) {
		if ($e != '.' && $e != '..' && $e != DB) {
			$d[$i][KEY] = $e;
			$d[$i][VALUE] = get_kvp($e, $k);
			if($d[$i][VALUE] === false) {
				array_pop($d);
			}
		}
	}
	closedir($h);
	set_kvp(DB, 'index_'.$n, serialize($d));
}

function get_index($n) {
	return unserialize(get_kvp(DB, 'index_'.$n));
}

// Template function
function tpl() {
	$f = func_get_args();
	$n = sizeof($f) - 1;
	$t = get_kvp(DB, $f[0]);
	for($i = 1; $i < $n; $i += 2) {
		$t = str_replace('{{'.$f[$i].'}}', $f[$i + 1], $t);
	}
	return $t;
}

// Templates
function footer() { ?>
	</main>
	<?php if (loggedin()): ?>
		<script src="<?= DATAPATH.JS ?>"></script>
	<?php endif ?>
	</body></html>
<?php }

function error($text) { ?>
	<section class="box">
		<h2><?= L_ERROR ?></h2>
		<p><?= $text ?>
		<p><a class="button" href="/"><?= L_BACK ?></a>
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
	return $t;
}

// Feed
if(isset($_GET['feed'])) {
	$p = @array_slice(get_index(D_POSTDATE), 0, POSTSPERPAGE);
	$u = "https://$_SERVER[HTTP_HOST]";
	$f = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	header('Content-type: application/atom+xml'); ?>
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title><?= SITENAME ?></title>
<?php if (!empty(SITEDESC)): ?>
<subtitle><?= SITEDESC ?></subtitle>
<?php endif ?>
<link href="<?= $u ?>" />
<link href="<?= $f ?>" rel="self"/>
<?php foreach($p as $m): ?>
<entry>
	<title><?= date(DATEFORMAT, $m[VALUE]) ?></title>
	<link href="<?= $u . '?p='.$m[KEY] ?>" />
	<content type="html"><![CDATA[<?= parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))) ?>]]></content>
	<updated><?= date('Y-m-d\TH:i:sP', $m[VALUE]) ?></updated>
	<id>urn:uuid:<?= $m[KEY] ?></id>
</entry>
<?php endforeach ?>
</feed><?php die();
}

// Header
?>
<!DOCTYPE html><html lang="<?= SITELANG ?>"><head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if (!empty(SITEDESC)): ?>
		<meta name="description" content="<?= SITEDESC ?>">
	<?php endif ?>

	<title><?= SITENAME ?></title>

	<link href="/?feed" type="application/atom+xml" title="<?= SITENAME ?> feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="<?= DATAPATH.CSS ?>" media="screen">

</head>

<body itemscope itemtype="http://schema.org/Blog">

<header>
	<div>
		<h1 itemprop="name">
			<?php if (!empty($_GET)): ?>
				<a href="/"><?= SITENAME ?></a>
			<?php else: ?>
				<?= SITENAME ?>
			<?php endif ?>
		</h1>
		<?php if (!empty(SITEDESC)): ?>
			<p itemprop="description"><?= SITEDESC ?></p>
		<?php endif ?>
	</div>

	<form class="search" action="/" method="get" role="search">
		<input type="text" name="s" aria-label="<?= L_SEARCH ?>">
		<button type="submit"><?= L_SEARCH ?></button>
	</form>
</header>

<main>
<?php

// Cookie
function set_cookie() {
	$identifier = bin2hex(random_bytes('64'));
	set_kvp(DB, COOKIE, $identifier);
	setcookie('vicco', $identifier, time()+(3600*24*30));
}
function delete_cookie() {
	delete_kvp(DB, COOKIE);
	setcookie('vicco', '', time()-(3600*24*30));
}

// Login
function loggedin() {
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_COOKIE['vicco']) && $_COOKIE['vicco'] === tpl(COOKIE)) {
		return true;
	}
}
if(isset($_GET['login'])) {
	if(loggedin()) {
		rmain();
	} else { ?>
		<form class="box login" action="/" method="post">
			<div>
				<label for="username"><?= L_USERNAME ?></label>
				<input type="text" id="username" name="username" autocomplete="username">
			</div>
			<div>
				<label for="passphrase"><?= L_PASSPHRASE ?></label>
				<input type="password" id="passphrase" name="passphrase" autocomplete="current-password">
			</div>
			<button type="submit" name="login"><?= L_LOGIN ?></button>
		</form>
	<?php
		footer();
		die();
	}
}
if(isset($_POST['login'])) {
	if(hash_equals(USERNAME, $_POST['username']) && hash_equals(PASSPHRASE, $_POST['passphrase'])) { 
		$_SESSION['loggedin'] = true;
		set_cookie();
		rmain();
	} else {
		error(L_ERROR_LOGIN);
	}
}
if(loggedin()) {
	// Submit posts
	if(isset($_POST['submit'])) {
		$r = 0;
		if(empty($_POST[D_POSTCONTENT])) {
			error(L_ERROR_EMPTY);
		}
		if(empty($_POST[D_POSTID])) {
			$r = create_record(uniqid());
			set_kvp($r, D_POSTDATE, time());
		} else {
			if(!record_exists($_POST[D_POSTID])) {
				error(L_ERROR_POSTEXISTS);
			}
			$r = $_POST[D_POSTID];
		}
		set_kvp($r, D_POSTCONTENT, $_POST[D_POSTCONTENT]);
		create_index(D_POSTDATE, D_POSTDATE);
	}

	// Delete posts
	if(isset($_POST['delete'])) {
		record_delete($_POST['postid']);
		create_index(D_POSTDATE, D_POSTDATE);
	} 

	// Edit posts
	function editing() {
		if (isset($_GET['edit'])) {
			return true;
		}
	}

	if (editing() && !record_exists($_GET['edit'])) {
		error(L_ERROR_POSTNONEXISTENT);
	} ?>

	<form class="box panel" action="/" method="post">
		<input type="hidden" name="postid" id="postid" value="<?= (editing() ? $_GET['edit'] : '') ?>">
		<textarea id="postcontent" name="postcontent" placeholder="<?= L_PLACEHOLDER ?>" aria-label="<?= L_POSTCONTENT ?>" spellcheck="false" rows="1" autofocus><?= (editing() ? get_kvp($_GET['edit'], D_POSTCONTENT) : '') ?></textarea>

		<div class="panel-meta row">
			<button type="submit" id="submit" name="submit"><?= L_PUBLISH ?></button>
			<button type="submit" name="logout"><?= L_LOGOUT ?></button>
		</div>
	</form>

<?php } elseif(isset($_POST['submit']) || isset($_POST['delete']) || (isset($_GET['edit']))) {
	error(L_ERROR_HACKER);
}

// Logout
if(isset($_POST['logout'])) {
	session_destroy();
	delete_cookie();
	rmain();
}

// Posts
$p = get_index(D_POSTDATE);

// Search
if(!empty($_GET['s'])) {
	$s = explode(' ',$_GET['s']);
	foreach($p as $k => $m) {
		$c = strtolower(parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))));
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
	error(L_ERROR_NORESULTS);
}

// Sorting
uasort($p, function($a, $b) {
	if($a[VALUE] == $b[VALUE]) {
		return 0;
	} else {
		return $b[VALUE] <=> $a[VALUE];
	}
});

if(isset($_GET['p']) && record_exists($_GET['p'])) {
	$p = array(array(VALUE => get_kvp($_GET['p'], D_POSTDATE), KEY => $_GET['p']));
}

$p = @array_slice($p, $_GET['skip'], POSTSPERPAGE);

if(!isset($_GET['edit'])) {
	foreach($p as $m): ?>
		<article class="box post" itemscope itemtype="https://schema.org/BlogPosting">
			<p class="post-text" itemprop="articleBody"><?= parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))) ?></p>
			<footer class="post-meta row">
				<div>
					<a href="?p=<?= $m[KEY] ?>" itemprop="url">
						<time datetime="<?= date('Y-m-d H:i:s', $m[VALUE]) ?>" itemprop="datePublished" pubdate><?= date(DATEFORMAT, $m[VALUE]) ?></time>
					</a>
				</div>
				<?php if (loggedin()): ?>
					<form class="admin row" action="/" method="post">
					<input type="hidden" name="postid" value="<?= $m[KEY] ?>">
					<a class="button" href="?edit=<?= $m[KEY] ?>"><?= L_EDIT ?></a>
					<button type="submit" class="delete" name="delete"><?= L_DELETE ?></button>
				</form>
				<?php endif ?>
			</footer>
		</article>
	<?php endforeach;
}

// Navigation
if(!isset($_GET['p']) && !isset($_GET['edit']) && $results >= POSTSPERPAGE) { ?>
	<nav class="row">
		<?php if (@$_GET['skip'] > 0): ?>
			<a href="?skip=<?= (@$_GET['skip'] > 0 ? @$_GET['skip'] - POSTSPERPAGE : 0).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><?= L_NEWER ?></a>
		<?php endif ?>
		<?php if (@$_GET['skip'] + POSTSPERPAGE < $results): ?>
			<a href="?skip=<?= (@$_GET['skip'] + POSTSPERPAGE < $results ? @$_GET['skip'] + POSTSPERPAGE : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><?= L_OLDER ?></a>
		<?php endif ?>
	</nav>
<?php }

// Footer
footer();

?>
