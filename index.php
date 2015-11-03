<?php

// Blog name
const SITENAME = 'vicco';

// Blog description
const SITEDESC = 'Yet another microblog';

// Blog language (ISO 639-1)
const SITELANG = 'en';

// Blog address
const PAGEHOME = 'http://localhost/vicco/';

// Username
const USERNAME = 'user';

// Password (CHANGE THIS)
const PASSWORD = 'pass';

// Posts per page
const POSTSPERPAGE = 10;

// Only change these if you know what you’re doing
const DATAPATH = 'data/';
const KEY = 'key';
const VALUE = 'value';
const B = '_template';
const T_HEADER = 'template_header';
const T_FOOTER = 'template_footer';
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
			die('Can\'t create database. Create directory "'.DATAPATH.'" and make it writeable.');
		}
	}
	create_record(B);
	create_index(D_POSTDATE,D_POSTDATE);
	set_kvp(B,T_HEADER, <<< 'EOD'
<!DOCTYPE html>
<html lang="{{SITELANG}}">
<head>

	<title>{{SITENAME}}</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link href="{{PAGEHOME}}?feed" type="application/atom+xml" title="{{SITENAME}} feed" rel="alternate" />

	<style type="text/css">
		*:focus { outline: thin solid }
		body { background-color: white; color: black; font: 16px/1.5 monospace; max-width: 50rem; padding: 0 1rem; margin: 0 auto; }
		a { color: #678; font-weight: bold; }
		a.perma { text-decoration: none; }
		code { background-color: #ced3db; }
		input, textarea { font: 16px/1.5 monospace; }
		form div { margin-bottom: 1rem; }
		textarea { background-color: #f2f4f7; width: 100%; height: 8rem; border: 0; padding: 0; }
		textarea:focus { outline: none; }
		header { text-align: center; margin: 2rem 0; }
		h1 { margin: 0; }
		article, .new { background-color: #f2f4f7; position: relative; padding: 2rem; margin-bottom: 2rem; border-bottom: 3px solid #ced3db; border-radius: 5px; }
		.meta { color: #666; }
		.right { text-align: right; float: right; }
		nav { float: left; }
		nav a { display: inline-block; padding: 0 .5rem .5rem; }
		footer { margin-bottom: 1rem; }
		.error { color: red; }
		.hidden { clip: rect(0 0 0 0); overflow: hidden; width: 1px; height: 1px; padding: 0; margin: -1px; position: absolute; border: 0; }
	</style>

</head>

<body>

	<header>
		<h1><a href="{{PAGEHOME}}">{{SITENAME}}</a></h1>
		<span>{{SITEDESC}}</span>
	</header>

EOD
	);
	set_kvp(B,T_POST, <<< 'EOD'

<article>
	<p>{{POSTCONTENT}}</p>
	<div class="meta">
		<a href="?ts={{POSTID}}" class="perma" title="Permalink">&#9733;</a>
		<time datetime="{{POSTDATETIME}}">{{POSTDATE}}</time>
		<div class="right">
			<a href="?edit={{POSTID}}">Edit</a>
			<a href="?delete={{POSTID}}">Delete</a>
		</div>
	</div>
</article>

EOD
	);
	set_kvp(B,T_ADMIN, <<< 'EOD'

<form class="new" action="index.php" method="post">
	<div>
		<label class="hidden" for="postcontent">Post content</label>
		<textarea id="postcontent" name="postcontent" placeholder="Start typing &hellip;" autofocus>{{POSTCONTENT}}</textarea>
	</div>

	<input type="hidden" name="postid" value="{{POSTID}}" />
	<input type="submit" name="submitpost" value="Publish" />

	<div class="right">
		<a href="?rbindex">Refresh</a>
		<a href="?logout">Logout</a>
	</div>
</form>

EOD
	);
	set_kvp(B,T_ADMINLOGIN, <<< 'EOD'

<form action="index.php" method="post">
	<fieldset>
		<legend>Administration</legend>
		<div>
			<label for="username">Username</label><br />
			<input type="text" id="username" name="username" />
		</div>
		<div>
			<label for="password">Password</label><br />
			<input type="password" id="password" name="password" />
		</div>
		<input name="login" type="submit" value="Login" />
	</fieldset>
</form>

EOD
	);
	set_kvp(B,T_FAIL, <<< 'EOD'

<p class="error"><strong>Error:</strong> Something went wrong</p>

EOD
	);
	set_kvp(B,T_NAV, <<< 'EOD'

<nav>
	<a href="?skip={{NEXT}}" class="next">&larr; Newer posts</a>
	<a href="?skip={{PREV}}" class="prev">Older posts &rarr;</a>
</nav>

EOD
	);
	set_kvp(B,T_FOOTER, <<< 'EOD'

<footer class="right">
	<form action="index.php" method="get">
		<label class="hidden" for="search">Search</label>
		<input type="text" id="search" name="s" />
		<input type="submit" value="Search" />
	</form><br />
	<a href="?feed">Feed</a>
	<a href="?login">Login</a>
</footer>

<!-- vicco: {{USED}} KB used -->

</body>
</html>

EOD
	);
	set_kvp(B,ATOM_HEADER, <<< 'EOD'
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">

	<title>{{SITENAME}}</title>
	<subtitle>{{SITEDESC}}</subtitle>
	<link href="{{SITEURL}}" />

EOD
	);
	set_kvp(B,ATOM_FOOTER, <<< 'EOD'

</feed>

EOD
	);
	set_kvp(B,ATOM_ITEM, <<< 'EOD'

<entry>
	<title type="html"><![CDATA[{{POSTCONTENT}}]]></title>
	<link href="{{LINK}}" />
	<updated>{{DATE}}</updated>
</entry>

EOD
	);
	set_kvp(B, 'firstuse', 1);
}

// Database
function create_record($r) {
	$r = sanitize_key($r);
	if(!record_exists($r)) {
		mkdir(DATAPATH.$r);
	}
	return $r;
}
function set_kvp($r, $k, $v) {
	file_put_contents(DATAPATH.sanitize_key($r).'/'.sanitize_key($k),$v);
}
function get_kvp($r, $k) {
	$p=DATAPATH.sanitize_key($r).'/'.sanitize_key($k);
	return file_exists($p)?file_get_contents($p) : false; // check
}
function delete_kvp($r, $kvp) {
	unlink(DATAPATH.sanitize_key($r).'/'.sanitize_key($kvp));
}
function record_exists($p) {
	$p=sanitize_key($p);
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
	set_kvp(B,'index_'.$n,serialize($d));
}

function get_index($n) {
	return unserialize(get_kvp(B, 'index_'.$n));
}

// Templates
function tpl() {
	$f = func_get_args();
	$n = sizeof($f)-1;
	$t = get_kvp(B, $f[0]);
	for($i = 1; $i < $n; $i += 2) {
		$t = str_replace('{{'.$f[$i].'}}', $f[$i+1], $t);
	}
	return $t;
}
function tpl_set($t, $w, $r) {
	return str_replace('{{'.$w.'}}', $r, $t);	
}
function fail() {
	echo tpl(T_HEADER, 'SITENAME', SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'PAGEHOME', T_HEADER);
	echo tpl(T_FAIL);
	echo tpl(T_FOOTER);
	die();
}

// Administration
function rmain() {
	header('Location: '.$_SERVER['PHP_SELF']);
	die();
}
if(isset($_POST['login'])) {
	if($_POST['username'] === USERNAME && $_POST['password'] === PASSWORD) {
		$_SESSION['loggedin'] = true;
	}
	rmain();
}
if(isset($_GET['logout'])) {
	session_destroy();
	rmain();
}
if(@$_SESSION['loggedin'] === true) {
	if(isset($_POST['submitpost'])) {
		$r = 0;
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
	if(isset($_GET['delete'])) {
		record_delete($_GET['delete']);
		create_index(D_POSTDATE, D_POSTDATE);
	}
	if(isset($_GET['dc'])) {
		if(!record_exists($cfl)) {
			fail();
		}
		delete_kvp($cfl,$_GET['cid'].'_'.D_NAME);
		delete_kvp($cfl,$_GET['cid'].'_'.D_POSTDATE);
	}
	if(isset($_GET['rbindex'])) {
		create_index(D_POSTDATE, D_POSTDATE);
	}
}

// Markdown
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

// Feed
if(isset($_GET['feed'])) {
	$p = @array_slice($p, 0, POSTSPERPAGE);
	echo tpl(ATOM_HEADER, 'SITENAME' ,SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'SITEURL', PAGEHOME);
	foreach($p as $m) {
		echo tpl(ATOM_ITEM, 'POSTTITLE', get_kvp($m[KEY], D_POSTTITLE), 'POSTCONTENT', parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))), 'LINK', PAGEHOME.'?ts='.$m[KEY], 'DATE', date('Y-m-d H:i:s', $m[VALUE]));
	}
	echo tpl(ATOM_FOOTER);
	die();
}
echo tpl(T_HEADER, 'SITENAME', SITENAME, 'SITEDESC', SITEDESC, 'SITELANG', SITELANG, 'PAGEHOME', PAGEHOME);
if(isset($_GET['login'])) {
	echo tpl(T_ADMINLOGIN);
	die();
}
if(@$_SESSION['loggedin'] === true) {
	if(isset($_GET['edit'])) {
		if(!record_exists($_GET['edit'])) {
			fail();
		}
		echo tpl(T_ADMIN, 'POSTTITLE', get_kvp($_GET['edit'], D_POSTTITLE), 'POSTCONTENT', get_kvp($_GET['edit'], D_POSTCONTENT), 'POSTID', $_GET['edit'], 'SELF', $_SERVER['PHP_SELF']);
	} else {
		echo tpl(T_ADMIN, 'POSTTITLE' , '', 'POSTCONTENT', '', 'POSTID', '', 'SELF', $_SERVER['PHP_SELF']);
	}
}
if(isset($_GET['ts']) && record_exists($_GET['ts'])) {
	$o = 1;
	$p = array(array(VALUE => get_kvp($_GET['ts'], D_POSTDATE), KEY => $_GET['ts']));
}
$p = @array_slice($p, $_GET['skip'], POSTSPERPAGE);
foreach($p as $m) {
	echo tpl(T_POST, 'POSTID', $m[KEY], 'POSTTITLE', get_kvp($m[KEY],D_POSTTITLE), 'POSTCONTENT', parse(nl2br(get_kvp($m[KEY], D_POSTCONTENT))), 'POSTDATE', date('d M Y H:i:s', $m[VALUE]), 'POSTDATETIME', date('Y-m-d H:i:s', $m[VALUE]));
}
echo tpl(T_NAV, 'NEXT', (@$_GET['skip']>0 ? @$_GET['skip'] - POSTSPERPAGE:0).'&amp;s='.@urlencode($_GET['s']), 'PREV', (@$_GET['skip'] + POSTSPERPAGE < $sp ? @$_GET['skip'] + POSTSPERPAGE : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']));
echo tpl(T_FOOTER, 'USED', intval(memory_get_usage()/1024));
?>
