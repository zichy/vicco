<?php

// Blog name
const SITENAME = 'vicco';

// Description
const SITEDESC = 'Yet another microblog';

// Language (ISO 639-1)
const SITELANG = 'en';

// URL
const PAGEHOME = 'https://localhost';

// Username
const USERNAME = 'user';

// Password (CHANGE THIS)
const PASSWORD = 'pass';

// Posts per page
const POSTSPERPAGE = 10;

// Only change these if you know what you’re doing
const DATAPATH = 'vicco/';
const KEY = 'key';
const VALUE = 'value';
const B = '_cache';
const CSS = 'style.css';
const T_HEADER = 'template_header';
const T_FOOTER = 'template_footer';
CONST T_SEARCH = 'template_search';
const T_POST = 'template_post';
const T_ADMIN = 'template_addpost';
const T_FAIL = 'template_fail';
const T_NAV = 'template_nav';
const ATOM_FOOTER = 'atom_footer';
const ATOM_HEADER = 'atom_header';
const ATOM_ITEM = 'atom_item';
const T_ADMINLOGIN = 'template_login';
const D_POSTTITLE = 'posttitle';
const D_POSTCONTENT = 'postcontent';
const D_POSTDATE = 'postdate';
const D_POSTDATETIME = 'postdatetime';
const D_NAME = 'name';
const D_POSTID = 'postid';

session_start();

// Installation
if(get_kvp(B,'firstuse') === false) {
	if(!record_exists('')) {
		if(!mkdir(DATAPATH)) {
			die('Can’t create database. Create directory "'.DATAPATH.'" and make it writeable.');
		}
	}
	create_record(B);
	create_index(D_POSTDATE, D_POSTDATE);

	set_file(B, CSS, <<< 'EOD'

*:focus {
	outline: thin solid;
}
body,
textarea,
input,
code {
	font-family: 'Menlo', 'Monaco', 'Courier New', 'Courier', monospace;
	font-size: 1rem;
	line-height: 1.5;
}
body {
	background-color: white;
	color: black;
	max-width: 50rem;
	padding: 0 1rem;
	margin: 0 auto;
}
main {
	display: block;
}
a {
	color: blue;
}
a:hover,
a:focus {
	text-decoration: none;
	outline: thin solid;
}
h1,
h2 {
	margin: 0;
}
code {
	background-color: #fe9;
}
input {
	border: 1px solid gray;
}
textarea {
	background-color: white;
	width: 100%;
	height: 8rem;
	border: 0;
	padding: 0;
}
textarea:focus {
	outline: none;
}
header,
footer {
	margin: 2rem 0;
}
header {
	text-align: center;
}
header div {
	position: absolute;
	top: .5rem;
	right: .5rem;
}
footer {
	color: gray;
	clear: both;
}
article,
.box,
.panel {
	padding: 2rem;
	margin-bottom: 2rem;
	border: 1px solid gray;
}
article p {
	margin-top: 0;
}
fieldset {
	padding: 0;
	margin: 0;
	border: none;
}
legend,
form div {
	margin-bottom: 1rem;
}
legend {
	font-weight: bold;
}
nav {
	float: left;
}
nav a {
	display: inline-block;
	margin-right: 1rem;
}
.right {
	text-align: right;
	float: right;
}
.admin {
	display: none;
}
.panel ~ * .admin {
	display: block;
}
.error {
	color: red;
}
.error p {
	margin-bottom: 0;
}
.hidden {
	clip: rect(0 0 0 0);
	overflow: hidden;
	width: 1px;
	height: 1px;
	padding: 0;
	margin: -1px;
	position: absolute;
	border: 0;
}

EOD
	); set_kvp(B, T_HEADER, <<< 'EOD'
<!DOCTYPE html>
<html lang="{{SITELANG}}">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>{{SITENAME}}</title>

	<link href="{{PAGEHOME}}?feed" type="application/atom+xml" title="{{SITENAME}} feed" rel="alternate" />
	<link rel="stylesheet" type="text/css" href="{{DATAPATH}}{{B}}/{{CSS}}" />

</head>

<body itemscope itemtype="http://schema.org/Blog">

<header>
	<h1 itemprop="name"><a href="{{PAGEHOME}}">{{SITENAME}}</a></h1>
	<p itemprop="description">{{SITEDESC}}</p>

	<div>
		<a href="?feed">Feed</a>
		<a href="?login">Login</a>
	</div>
</header>

<main>

EOD
	); set_kvp(B,T_POST, <<< 'EOD'

<article itemscope itemtype="https://schema.org/BlogPosting">
	<p itemprop="articleBody">{{POSTCONTENT}}</p>

	<aside>
		<a href="?ts={{POSTID}}" itemprop="url" title="Permalink">
			<time datetime="{{POSTDATETIME}}" itemprop="datePublished" pubdate>{{POSTDATE}}</time>
		</a>

		<form action="index.php" method="post" class="admin right">
			<input type="hidden" name="postid" value="{{POSTID}}" />
			<a href="?edit={{POSTID}}">Edit</a>
			<button type="submit" name="delete">Delete</button>
		</form>
	</aside>
</article>

EOD
	); set_kvp(B, T_ADMIN, <<< 'EOD'

<form class="panel" action="index.php" method="post">
	<div>
		<label class="hidden" for="postcontent">Post content</label>
		<textarea id="postcontent" name="postcontent" placeholder="Start typing &hellip;" autofocus>{{POSTCONTENT}}</textarea>
	</div>

	<input type="hidden" name="postid" value="{{POSTID}}" />

	<button type="submit" name="submitpost"><strong>Publish</strong></button>
	<button type="reset">Reset</button>

	<div class="right">
		<button type="submit" name="rbindex">Refresh</button>
		<button type="submit" name="logout">Logout</button>
	</div>
</form>

EOD
	); set_kvp(B, T_ADMINLOGIN, <<< 'EOD'

<form class="box" action="index.php" method="post">
	<fieldset>
		<legend>Please enter your credentials</legend>
		<div>
			<label for="username">Username</label><br />
			<input type="text" id="username" name="username" />
		</div>
		<div>
			<label for="password">Password</label><br />
			<input type="password" id="password" name="password" />
		</div>
		<button type="submit" name="login">Login</button>
	</fieldset>
</form>

EOD
	); set_kvp(B, T_FAIL, <<< 'EOD'

<section class="box error">
	<h2>Error</h2>
	<p>Something went wrong. Please try again.</p>
</section>

EOD
	); set_kvp(B, T_NAV, <<< 'EOD'

<nav>
	<a href="?skip={{NEXT}}" class="next">&larr; Newer</a>
	<a href="?skip={{PREV}}" class="prev">Older &rarr;</a>
</nav>

EOD
	); set_kvp(B, T_SEARCH, <<< 'EOD'

<form class="right" action="index.php" method="get" role="search">
	<label class="hidden" for="search">Search</label>
	<input type="text" id="search" name="s" />
	<button type="submit">Search</button>
</form><br />

EOD
	); set_kvp(B, T_FOOTER, <<< 'EOD'

</main>

<footer>
	<p>vicco: {{USED}} kB used, {{SERVER}}</p>
</footer>

</body>
</html>

EOD
	); set_kvp(B, ATOM_HEADER, <<< 'EOD'
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">

	<title>{{SITENAME}}</title>
	<subtitle>{{SITEDESC}}</subtitle>
	<link href="{{SITEURL}}" />

EOD
	); set_kvp(B, ATOM_FOOTER, <<< 'EOD'

</feed>

EOD
	); set_kvp(B, ATOM_ITEM, <<< 'EOD'

<entry>
	<title type="html"><![CDATA[{{POSTCONTENT}}]]></title>
	<link href="{{LINK}}" />
	<updated>{{DATE}}</updated>
</entry>

EOD
	); set_kvp(B, 'firstuse', 1);
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
		if ($e != '.' && $e != '..' && $e != B) {
			$d[$i][KEY] = $e;
			$d[$i][VALUE] = get_kvp($e, $k);
			if($d[$i][VALUE] === false) {
				array_pop($d);
			}
		}
	}
	closedir($h);
	set_kvp(B, 'index_'.$n, serialize($d));
}

function get_index($n) {
	return unserialize(get_kvp(B, 'index_'.$n));
}

// Templates
function tpl() {
	$f = func_get_args();
	$n = sizeof($f) - 1;
	$t = get_kvp(B, $f[0]);
	for($i = 1; $i < $n; $i += 2) {
		$t = str_replace('{{'.$f[$i].'}}', $f[$i + 1], $t);
	}
	return $t;
}
function tpl_set($t, $w, $r) {
	return str_replace('{{'.$w.'}}', $r, $t);	
}

// Header template
function tpl_header() {
	echo tpl(T_HEADER, 'SITENAME', SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'PAGEHOME', PAGEHOME, 'DATAPATH', DATAPATH, 'B', B, 'CSS', CSS);
}

// Footer template
function tpl_footer() {
	echo tpl(T_FOOTER, 'USED', intval(memory_get_usage() / 1024), 'SERVER', $_SERVER['SERVER_SOFTWARE']);
}

// Error handling
function fail() {
	echo tpl(T_FAIL);
	tpl_footer();
	die();
}

// Return to index
function rmain() {
	header('Location: '.$_SERVER['PHP_SELF']);
	die();
}

// Text formatting
function parse($t) {
	$t = preg_replace('/(\*\*|__)(.*?)\1/', '<strong>\2</strong>', $t);
	$t = preg_replace('/(\*|_)(.*?)\1/','<em>\2</em>',$t);
	$t = preg_replace('/\~(.*?)\~/','<del>\1</del>',$t);
	$t = preg_replace('/\:\"(.*?)\"\:/','<q>\1</q>',$t);
	$t = preg_replace('/\@(.*?)\@/','<code>\1</code>',$t);
	$t = preg_replace('/\[([^\[]+)\]\(([^\)]+)\)/','<a href=\'\2\'>\1</a>',$t);
	$t = preg_replace('/\[(.*?)\]/','<a href=\'\1\'>\1</a>',$t);
	return $t;
}

// Feed
if(isset($_GET['feed'])) {
	$p = @array_slice(get_index(D_POSTDATE), 0, POSTSPERPAGE);
	header('Content-type: application/atom+xml');
	echo tpl(ATOM_HEADER, 'SITENAME', SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'SITEURL', PAGEHOME);
	foreach($p as $m) {
		echo tpl(ATOM_ITEM, 'POSTTITLE', get_kvp($m[KEY], D_POSTTITLE), 'POSTCONTENT', parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))), 'LINK', PAGEHOME.'?ts='.$m[KEY], 'DATE', date('Y-m-d H:i:s', $m[VALUE]));
	}
	echo tpl(ATOM_FOOTER);
	die();
}

// Show header
tpl_header();

// Show login
if(isset($_GET['login'])) {
	if(@$_SESSION['loggedin'] === true) {
		header('Location: '.$_SERVER['PHP_SELF']);
		die();
	} else {
		echo tpl(T_ADMINLOGIN);
		tpl_footer();
		die();
	}
}

// Login
if(isset($_POST['login'])) {
	if($_POST['username'] === USERNAME && $_POST['password'] === PASSWORD) {
		$_SESSION['loggedin'] = true;
		rmain();
	} else {
		fail();
	}
}
if(@$_SESSION['loggedin'] === true) {

	// Submit posts
	if(isset($_POST['submitpost'])) {
		$r = 0;
		if(empty($_POST[D_POSTCONTENT])) {
			fail();
		}
		if(empty($_POST[D_POSTID])) {
			$r = create_record(uniqid());
			set_kvp($r, D_POSTDATE, time());
		} else {
			if(!record_exists($_POST[D_POSTID])) {
				fail();
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

	if(isset($_GET['dc'])) {
		if(!record_exists($cfl)) {
			fail();
		}
		delete_kvp($cfl,$_GET['cid'].'_'.D_NAME);
		delete_kvp($cfl,$_GET['cid'].'_'.D_POSTDATE);
	}

	// Refresh
	if(isset($_POST['rbindex'])) {
		create_index(D_POSTDATE, D_POSTDATE);
	}

	// Edit posts
	if(isset($_GET['edit'])) {
		$e = $_GET['edit'];
		if(!record_exists($e)) {
			fail();
		}
		echo tpl(T_ADMIN, 'POSTTITLE', get_kvp($e, D_POSTTITLE), 'POSTCONTENT', get_kvp($e, D_POSTCONTENT), 'POSTID', $e, 'SELF', $_SERVER['PHP_SELF']);
	} else {
		echo tpl(T_ADMIN, 'POSTTITLE' , '', 'POSTCONTENT', '', 'POSTID', '', 'SELF', $_SERVER['PHP_SELF']);
	}
}

// Logout
if(isset($_POST['logout'])) {
	session_destroy();
	rmain();
}

$p = get_index(D_POSTDATE);

// Search
if(!empty($_GET['s'])) {
	$s = explode(' ',$_GET['s']);
	foreach($p as $k => $m) {
		$t = strtolower(get_kvp($m[KEY], D_POSTTITLE));
		$c = strtolower(parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))));
		$f = true;
		for($i = 0; $i < sizeof($s); $i++) {
			if(strpos($c, strtolower($s[$i])) === false && strpos($t, strtolower($s[$i])) === false) {
				$f = false;
				break;
			}
		}
		if(!$f) {
			unset($p[$k]);
		}
	}
}
$sp = sizeof($p);
$o = 0;

// Sorting
uasort($p, function($a, $b) {
	if($a[VALUE] == $b[VALUE]) {
		return 0;
	} else {
		return $a[VALUE] < $b[VALUE];
	}
});

if(isset($_GET['ts']) && record_exists($_GET['ts'])) {
	$o = 1;
	$p = array(array(VALUE => get_kvp($_GET['ts'], D_POSTDATE), KEY => $_GET['ts']));
}

$p = @array_slice($p, $_GET['skip'], POSTSPERPAGE);

foreach($p as $m) {
	echo tpl(T_POST, 'POSTID', $m[KEY], 'POSTTITLE', get_kvp($m[KEY],D_POSTTITLE), 'POSTCONTENT', parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))), 'POSTDATE', date('d M Y H:i:s', $m[VALUE]), 'POSTDATETIME', date('Y-m-d H:i:s', $m[VALUE]));
}

// Show navigation
echo tpl(T_NAV, 'NEXT', (@$_GET['skip']>0 ? @$_GET['skip'] - POSTSPERPAGE:0).'&amp;s='.@urlencode($_GET['s']), 'PREV', (@$_GET['skip'] + POSTSPERPAGE < $sp ? @$_GET['skip'] + POSTSPERPAGE : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']));

// Show search results
echo tpl(T_SEARCH);

// Show footer
tpl_footer();

?>
