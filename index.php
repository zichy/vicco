<?php

//************************************//

	// Site name
	const SITENAME = 'vicco';

	// Description
	const SITEDESC = 'Yet another microblog';

	// URL (with trailing slash)
	const PAGEHOME = 'https://localhost/blog/';

	// Username
	const USERNAME = 'admin';

	// Passphrase
	const PASSPHRASE = 'CHANGEME';

	// Language (ISO 639-1)
	const SITELANG = 'en';

	// Font family
	const FONTFAMILY = 'ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, "Liberation Mono", monospace';

	// Colors (hexadecimal)
	const BODYCOLOR = '#eee';
	const BOXCOLOR = '#fff';
	const TEXTCOLOR = '#000';
	const ACCENTCOLOR = '#00f';

	// Posts per page
	const POSTSPERPAGE = 10;

//************************************//

// System constants
const DATAPATH = 'vicco/';
const KEY = 'key';
const VALUE = 'value';
const TPL = 'tpl';
const CSS = 'style.css';
const JS = 'script.js';
const T_HEADER = 'header';
const T_FOOTER = 'footer';
const T_POST = 'post';
const T_ADMIN = 'addpost';
const T_ERROR = 'error';
const T_NEWER = 'nav_newer';
const T_OLDER = 'nav_older';
const T_ADMINLOGIN = 'login';
const ATOM_FOOTER = 'atom_footer';
const ATOM_HEADER = 'atom_header';
const ATOM_ITEM = 'atom_item';
const D_POSTCONTENT = 'postcontent';
const D_POSTDATE = 'postdate';
const D_POSTDATETIME = 'postdatetime';
const D_POSTID = 'postid';
const COOKIE = 'cookie';

session_start();

// Installation
if(get_kvp(TPL, 'firstuse') === false) {
	if(!record_exists('')) {
		if(!mkdir(DATAPATH)) {
			die('No write permissions to create the folder "'.DATAPATH.'".');
		}
	}
	create_record(TPL);
	create_index(D_POSTDATE, D_POSTDATE);

	set_file(null, CSS, <<< 'EOD'
:root {
	--f-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, 'Liberation Mono', monospace;
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
	outline: 2px solid var(--c-accent);
	outline-offset: 2px;
}
body {
	background-color: var(--c-body);
	color: var(--c-text);
	font-family: var(--f-family);
	font-size: var(--f-size);
	line-height: var(--f-line);
	max-width: 76.8rem;
	padding: 0 2rem;
	margin: 4rem auto;
}
a {
	color: var(--c-accent);
}
a:is(:hover, :focus) {
	background-color: var(--c-accent);
	color: var(--c-box);
	text-decoration: none;
}
code {
	background-color: var(--c-box);
	font-family: var(--f-family);
	font-size: var(--f-size);
}
label {
	color: var(--c-accent);
	font-weight: bold;
	display: block;
	padding-bottom: 0.5rem;
}
input {
	font-family: var(--f-family);
	font-size: var(--f-size);
	width: 100%;
	height: 3.5rem;
	padding: 0 1rem;
	border: 2px solid var(--c-accent);
	border-radius: 0.5rem;
}
textarea {
	background-color: var(--c-box);
	font-family: var(--f-family);
	font-size: var(--f-size);
	line-height: var(--f-line);
	display: block;
	width: 100%;
	height: 10rem;
	padding: 0;
	margin-bottom: 2rem;
	border: 0;
	resize: none;
}
textarea:focus {
	outline: none;
}
:is(button, .button) {
	background-color: var(--c-accent);
	color: var(--c-box);
	font-family: var(--f-family);
	font-size: var(--f-size);
	font-weight: bold;
	line-height: 1;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	text-decoration: none;
	height: 3.5rem;
	padding: 0 1rem;
	margin: 0;
	border: 0;
	border-radius: 0.5rem;
	cursor: pointer;
	touch-action: manipulation;
	user-select: none;
	-webkit-user-select: none;
}
:is(button, .button):hover {
	text-decoration: underline;
}
header {
	margin-bottom: 2rem;
}
@media (min-width: 768px) {
	header {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 4rem;
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
@media (max-width: 767px) {
	.search {
		margin-top: 2rem;
	}
}
.search input {
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}
.search button {
	border-top-left-radius: 0;
	border-bottom-left-radius: 0;
}
.box {
	background-color: var(--c-box);
	padding: 3rem 3rem 2rem;
	margin-bottom: 2rem;
	border-radius: 1rem;
}
.box > *:first-child {
	margin-top: 0;
}
.box > *:last-child {
	margin-bottom: 0;
}
.post footer a {
	text-decoration: none;
}
.post-meta {
	display: flex;
	flex-direction: column;
	gap: 2rem;
}
@media (min-width: 768px) {
	.post-meta {
		flex-direction: row;
		align-items: end;
		justify-content: space-between;
	}
}
@media (min-width: 768px) {
	.site-meta {
		display: flex;
		justify-content: space-between;
	}
}
.login div {
	margin-bottom: 2rem;
}
.panel ~ * .admin {
	display: block;
}
.panel-meta {
	display: flex;
	gap: 1rem;
}
@media (min-width: 768px) {
	.panel-meta {
		justify-content: space-between;
	}
}
nav {
	display: flex;
	justify-content: center;
	gap: 1rem;
}

EOD
	);
	set_file(null, JS, <<< 'EOD'
function resizeArea($el) {
	const heightLimit = 500;
	$el.style.height = '';
	$el.style.height = Math.min($el.scrollHeight, heightLimit) + 'px';
}

const $textarea = document.getElementById('postcontent');

if($textarea) {
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
			if(confirm('Do you really want to delete this post?')) {
				$form.submit();
			} else {
				e.preventDefault();
			}
		})
	});
}

EOD
	);
	set_kvp(TPL, T_HEADER, <<< 'EOD'
<!DOCTYPE html>
<html lang="{{SITELANG}}">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{{SITENAME}}</title>

	<link href="{{PAGEHOME}}?feed" type="application/atom+xml" title="{{SITENAME}} feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="{{DATAPATH}}{{CSS}}" media="screen">

	<style>:root { --f-family: {{FONTFAMILY}}; --c-body: {{BODYCOLOR}}; --c-box: {{BOXCOLOR}}; --c-text: {{TEXTCOLOR}}; --c-accent: {{ACCENTCOLOR}}; }</style>

</head>

<body itemscope itemtype="http://schema.org/Blog">

<header>
	<div>
		<h1 itemprop="name"><a href="{{PAGEHOME}}">{{SITENAME}}</a></h1>
		<p itemprop="description">{{SITEDESC}}</p>
	</div>

	<form class="search" action="{{SCRIPTNAME}}" method="get" role="search">
		<input type="text" name="s" aria-label="Search terms">
		<button type="submit">Search</button>
	</form>
</header>

<main>

EOD
	); set_kvp(TPL, T_POST, <<< 'EOD'

<article class="box post" itemscope itemtype="https://schema.org/BlogPosting">
	<p itemprop="articleBody">{{POSTCONTENT}}</p>

	<footer class="post-meta">
		<div>
			<a href="?p={{POSTID}}" itemprop="url" title="Permalink">
				<time datetime="{{POSTDATETIME}}" itemprop="datePublished" pubdate>{{POSTDATE}}</time>
			</a>
		</div>

		<form class="admin" action="{{SCRIPTNAME}}" method="post" hidden>
			<input type="hidden" name="postid" value="{{POSTID}}">
			<a class="button" href="?edit={{POSTID}}">Edit</a>
			<button type="submit" class="delete" name="delete">Delete</button>
		</form>
	</footer>
</article>

EOD
	); set_kvp(TPL, T_ADMIN, <<< 'EOD'

<form class="box panel" action="{{SCRIPTNAME}}" method="post">
	<input type="hidden" name="postid" value="{{POSTID}}">

	<div>
		<textarea id="postcontent" name="postcontent" placeholder="Start typing &hellip;" aria-label="Post content" spellcheck="false" autofocus>{{POSTCONTENT}}</textarea>
	</div>

	<div class="panel-meta">
		<button type="submit" name="submitpost">Publish</button>

		<div>
			<button type="reset" id="reset">Reset</button>
			<button type="submit" name="logout">Logout</button>
		</div>
	</div>
</form>

EOD
	); set_kvp(TPL, T_ADMINLOGIN, <<< 'EOD'

<form class="box login" action="{{SCRIPTNAME}}" method="post">
	<h2>Administration</h2>
	<div>
		<label for="username">Username</label>
		<input type="text" id="username" name="username" autocomplete="username">
	</div>
	<div>
		<label for="passphrase">Passphrase</label>
		<input type="password" id="passphrase" name="passphrase" autocomplete="current-password">
	</div>
	<button type="submit" name="login">Login</button>
</form>

EOD
	); set_kvp(TPL, T_ERROR, <<< 'EOD'

<section class="box error">
	<h2>Error</h2>
	<p>{{ERRORTEXT}}
	<p><a class="button" href="{{SCRIPTNAME}}">Go back</a>
</section>

EOD
	); set_kvp(TPL, T_NEWER, <<< 'EOD'

<a href="?skip={{NEWER}}" class="button">Newer</a>

EOD
	); set_kvp(TPL, T_OLDER, <<< 'EOD'

<a href="?skip={{OLDER}}" class="button">Older</a>

EOD
	); set_kvp(TPL, T_FOOTER, <<< 'EOD'

</main>

<script src="{{DATAPATH}}{{JS}}"></script>

</body>
</html>

EOD
	); set_kvp(TPL, ATOM_HEADER, <<< 'EOD'
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">

	<title>{{SITENAME}}</title>
	<subtitle>{{SITEDESC}}</subtitle>
	<link href="{{SITEURL}}">

EOD
	); set_kvp(TPL, ATOM_FOOTER, <<< 'EOD'

</feed>

EOD
	); set_kvp(TPL, ATOM_ITEM, <<< 'EOD'

<entry>
	<title type="html"><![CDATA[{{POSTCONTENT}}]]></title>
	<link href="{{LINK}}">
	<updated>{{DATE}}</updated>
</entry>

EOD
	); set_kvp(TPL, 'firstuse', 1);
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
		if ($e != '.' && $e != '..' && $e != TPL) {
			$d[$i][KEY] = $e;
			$d[$i][VALUE] = get_kvp($e, $k);
			if($d[$i][VALUE] === false) {
				array_pop($d);
			}
		}
	}
	closedir($h);
	set_kvp(TPL, 'index_'.$n, serialize($d));
}

function get_index($n) {
	return unserialize(get_kvp(TPL, 'index_'.$n));
}

// Template function
function tpl() {
	$f = func_get_args();
	$n = sizeof($f) - 1;
	$t = get_kvp(TPL, $f[0]);
	for($i = 1; $i < $n; $i += 2) {
		$t = str_replace('{{'.$f[$i].'}}', $f[$i + 1], $t);
	}
	return $t;
}
function tpl_set($t, $w, $r) {
	return str_replace('{{'.$w.'}}', $r, $t);	
}

// Templates
function tpl_header() {
	echo tpl(T_HEADER, 'SITENAME', SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'PAGEHOME', PAGEHOME, 'DATAPATH', DATAPATH, 'TPL', TPL, 'CSS', CSS, 'FONTFAMILY', FONTFAMILY, 'BODYCOLOR', BODYCOLOR, 'BOXCOLOR', BOXCOLOR, 'TEXTCOLOR', TEXTCOLOR, 'ACCENTCOLOR', ACCENTCOLOR, 'SCRIPTNAME', $_SERVER['SCRIPT_NAME']);
}
function tpl_footer() {
	echo tpl(T_FOOTER, 'DATAPATH', DATAPATH, 'JS', JS);
}
function tpl_error($text) {
	echo tpl(T_ERROR, 'ERRORTEXT', $text, 'SCRIPTNAME', $_SERVER['SCRIPT_NAME']);
	tpl_footer();
	die();
}

// Go to index
function rmain() {
	header('Location: '.$_SERVER['PHP_SELF']);
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
	header('Content-type: application/atom+xml');
	echo tpl(ATOM_HEADER, 'SITENAME', SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'SITEURL', PAGEHOME);
	foreach($p as $m) {
		echo tpl(ATOM_ITEM, 'POSTCONTENT', parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))), 'LINK', PAGEHOME.'?p='.$m[KEY], 'DATE', date('Y-m-d H:i:s', $m[VALUE]));
	}
	echo tpl(ATOM_FOOTER);
	die();
}

// Header
tpl_header();

// Cookie
function set_cookie() {
	$identifier = bin2hex(random_bytes('64'));
	set_kvp(TPL, COOKIE, $identifier);
	setcookie('vicco', $identifier, time()+(3600*24*30));
}
function delete_cookie() {
	delete_kvp(TPL, COOKIE);
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
		header('Location: '.$_SERVER['PHP_SELF']);
		die();
	} else {
		echo tpl(T_ADMINLOGIN, 'SCRIPTNAME', $_SERVER['SCRIPT_NAME']);
		tpl_footer();
		die();
	}
}
if(isset($_POST['login'])) {
	if(hash_equals(USERNAME, $_POST['username']) && hash_equals(PASSPHRASE, $_POST['passphrase'])) { 
		$_SESSION['loggedin'] = true;
		set_cookie();
		rmain();
	} else {
		tpl_error('The credentials are incorrect.');
	}
}
if(loggedin()) {
	// Submit posts
	if(isset($_POST['submitpost'])) {
		$r = 0;
		if(empty($_POST[D_POSTCONTENT])) {
			tpl_error('Your post must contain text.');
		}
		if(empty($_POST[D_POSTID])) {
			$r = create_record(uniqid());
			set_kvp($r, D_POSTDATE, time());
		} else {
			if(!record_exists($_POST[D_POSTID])) {
				tpl_error('An error occured.');
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

	// Refresh
	if(isset($_POST['rbindex'])) {
		create_index(D_POSTDATE, D_POSTDATE);
	}

	// Edit posts
	if(isset($_GET['edit'])) {
		$e = $_GET['edit'];
		if(!record_exists($e)) {
			tpl_error('The post you wish to edit does not exist.');
		}
		echo tpl(T_ADMIN, 'SCRIPTNAME', $_SERVER['SCRIPT_NAME'], 'POSTCONTENT', get_kvp($e, D_POSTCONTENT), 'POSTID', $e);
	} else {
		echo tpl(T_ADMIN, 'SCRIPTNAME', $_SERVER['SCRIPT_NAME'], 'POSTCONTENT', '', 'POSTID', '', 'JS', JS);
	}
} elseif(isset($_POST['submitpost']) || isset($_POST['delete']) || (isset($_GET['edit']))) {
	tpl_error('Nice try.');
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
	tpl_error('There are no search results.');
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
	foreach($p as $m) {
		echo tpl(T_POST, 'SCRIPTNAME', $_SERVER['SCRIPT_NAME'], 'POSTID', $m[KEY], 'POSTCONTENT', parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))), 'POSTDATE', date('d M Y H:i:s', $m[VALUE]), 'POSTDATETIME', date('Y-m-d H:i:s', $m[VALUE]));
	}
}

// Navigation
if(!isset($_GET['p']) && !isset($_GET['edit'])) {
	echo '<nav>';

	if(@$_GET['skip'] > 0) {
		echo tpl(T_NEWER, 'NEWER', (@$_GET['skip'] > 0 ? @$_GET['skip'] - POSTSPERPAGE:0).'&amp;s='.@urlencode($_GET['s']));
	}

	if(@$_GET['skip'] + POSTSPERPAGE < $results) {
		echo tpl(T_OLDER, 'OLDER', (@$_GET['skip'] + POSTSPERPAGE < $results ? @$_GET['skip'] + POSTSPERPAGE : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']));
	}

	echo '</nav>';
}

// Footer
tpl_footer();

?>
