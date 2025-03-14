<?php

/*
	SPDX-License-Identifier: BSD-2-Clause
	SPDX-FileCopyrightText: Copyright (c) 2015–2025 lawl(†), zichy
*/

// Login credentials
define('username', 'admin');
define('passphrase', 'CHANGEME');

// Localisation strings
class L10n {
	static $search = 'Search';
	static $title = 'Title';
	static $comment = 'Comment';
	static $placeholder = 'Start writing …';
	static $publish = 'Publish';
	static $draft = 'Draft';
	static $config = 'Config';
	static $save = 'Save';
	static $saveDraft = 'Save as Draft';
	static $logout = 'Logout';
	static $edit = 'Edit';
	static $delete = 'Delete';
	static $deleteWarning = 'Do you really want to delete this post?';
	static $older = 'Older';
	static $newer = 'Newer';
	static $username = 'Username';
	static $passphrase = 'Passphrase';
	static $feed = 'Feed';
	static $about = 'About';
	static $close = 'Close';
	static $login = 'Login';
	static $back = 'Go Back';
	static $error = 'Error';
	static $errorLogin = 'The credentials are incorrect.';
	static $errorEmpty = 'Your post must contain a title and a comment.';
	static $errorPostExists = 'A post with this ID already exists.';
	static $errorPostNonexistent = 'The post you wish to edit does not exist.';
	static $errorNoResults = 'No posts were found.';
	static $errorHacker = 'Nice try.';
	static $errorPermissions = 'No write permissions to create the folder ';
	static $introTitle = 'Welcome to vicco!';
	static $introComment = 'This is your new blog. Log in, have a look around and start posting.';
	static $setup = '**Welcome to vicco!** Please submit this form to setup your new blog.';
}

// System variables – do not change
class Sys {
	static $folder = 'vicco/';
	static $dbFolder = 'sys/';
	static $postsFolder = 'posts/';
	static $config = 'config.json';
	static $css = 'style.css';
	static $js = 'script.js';
	static $settings = [
		'name' => 'vicco',
		'desc' => 'Yet another blog',
		'favicon' => '🌱',
		'language' => 'en',
		'dateFormat' => 'd M Y, H:i',
		'aboutText' => 'Add information about your blog, yourself, or legal notes. Or just leave it blank.',
		'mastodonVerification' => 'https://mastodon.example/@account',
		'fediverseCreator' => '@account@mastodon.example',
		'postsPerPage' => '10',
		'postsFeed' => '20',
		'showLogin' => '1',
		'customCSS' => '',
	];
}

$blogUrl = 'https://'.$_SERVER['HTTP_HOST'];
$fullUrl = $blogUrl.$_SERVER['REQUEST_URI'];
$dbPath = Sys::$folder.Sys::$dbFolder;
$postsPath = Sys::$folder.Sys::$postsFolder;
$configPath = Sys::$folder.Sys::$config;

session_start();

// Installation
if (getEntry('installed') === false) {

	// Create folders
	if (!folderExists('')) {
		if (!mkdir(Sys::$folder)) {
			die(L10n::$errorPermissions.Sys::$folder);
		}
	}
	createFolder(Sys::$dbFolder);
	createFolder(Sys::$postsFolder);
	setIndex();

	// Intro post
	if (!getIndex()) {
		$post = new stdClass();
		$id = getID('6');
		$post->date = time();
		$post->draft = false;
		$post->title = htmlspecialchars(L10n::$introTitle);
		$post->comment = htmlspecialchars(L10n::$introComment);
		setPost($id, $post);
	}

	// Create assets
	setFile(Sys::$css, <<< 'EOD'
:root {
	--bg: Canvas;
	--meta-bg: #eee;
	--text: CanvasText;
	--meta: #666;
	--interactive: LinkText;
	--accent: #fe9;
	--sans: ui-sans-serif, sans-serif;
	--mono: ui-monospace, monospace;
	--size: 1.6rem;
	--line: 1.5;
	--border: 1px solid var(--meta);
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
	color: var(--text);
}
*:focus-visible {
	outline: 2px solid var(--interactive);
	outline-offset: 2px;
}
body {
	background-color: var(--meta-bg);
	color: var(--text);
	font-size: var(--size);
	font-family: var(--sans);
	line-height: var(--line);
	max-width: 768px;
	min-width: 375px;
	padding-inline: 2rem;
	margin: 4rem auto;
	overflow-x: hidden;
}
body:has(:popover-open) {
	overflow: hidden;
}
[popover] {
	display: none !important;
	grid-template-rows: min-content min-content;
	width: 500px;
	max-width: 100%;
	max-height: 90vh;
	padding: 2.5rem !important;
	position: fixed;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	margin: 0;
	border: 0;
}
[popover]:popover-open {
	display: grid !important;
}
[popover]::backdrop {
	background: rgb(0 0 0 / 75%);
}
main {
	display: grid;
	gap: 3rem;
}
@media (max-width: 768px) {
	main {
		margin-inline: -2rem;
	}
}
a {
	color: var(--interactive);
}
a:is(:hover, :focus-visible) {
	color: var(--interactive);
	background-color: var(--accent);
}
:is(h1, h2) {
	color: var(--interactive);
	font-size: 1.25em;
	line-height: 1.2;
	margin-block: 0;
}
h1 {
	line-height: 1;
	margin-block: 0;
}
:is(h1, h2) a {
	text-decoration: none;
}
:is(h1, h2) a:hover {
	text-decoration: underline;
}
label {
	display: block;
	padding-block-end: 0.5rem;
}
:is(code, input, button) {
	font-size: var(--size);
}
code {
	background-color: var(--accent);
}
blockquote {
	font-style: italic;
	padding-inline-start: 2rem;
	margin-inline: 0;
	border-left: 2px solid var(--meta);
}
blockquote p {
	margin-block: 0;
}
.form {
	display: flex;
	flex-direction: column;
	row-gap: 2rem;
}
::placeholder {
	color: var(--meta);
}
:is(input, textarea) {
	background-color: var(--bg);
	color: var(--text);
	font-family: var(--mono);
	font-size: var(--size);
	display: block;
	width: 100%;
	border: var(--border);
	border-radius: 0.5rem;
}
:is(input, textarea):focus-visible {
	border-color: var(--interactive);
	outline: 1px solid var(--interactive);
	outline-offset: 0;
}
input {
	height: 3.5rem;
	padding-inline: 1rem;
}
textarea {
	line-height: var(--line);
	padding: 0.45rem 1rem;
}
:is(button, .button) {
	background-color: var(--interactive);
	color: var(--bg);
	font-size: 0.85em;
	font-family: var(--sans);
	font-weight: bold;
	text-decoration: none;
	line-height: 1;
	display: inline-flex;
	align-self: start;
	align-items: center;
	justify-content: center;
	column-gap: 0.5rem;
	width: fit-content;
	padding: 0.75rem 1.25rem;
	border: 1px solid var(--interactive);
	border-radius: 0.5rem;
	cursor: pointer;
	touch-action: manipulation;
	user-select: none;
	-webkit-user-select: none;
}
:is(button, .button):is(:hover, :focus-visible) {
	background-color: var(--accent);
	color: var(--interactive);
}
.meta {
	color: var(--meta);
	margin-block: 0;
}
.row {
	display: flex;
	column-gap: 1.5rem;
}
.header {
	font-family: var(--sans);
	display: flex;
	gap: 2rem 4rem;
	padding-block-end: 2rem;
}
@media (max-width: 768px) {
	.header {
		flex-direction: column;
	}
}
@media (min-width: 769px) {
	.header {
		justify-content: space-between;
		align-items: end;
	}
}
.text {
	font-family: var(--mono);
	text-wrap: balance;
}
.text > *:first-child {
	margin-block-start: 0;
}
.text > *:last-child {
	margin-block-end: 0;
}
.box {
	background-color: var(--bg);
	display: grid;
	gap: 2rem;
	padding: 2rem 2rem 0;
	box-shadow: 0 1rem 1rem rgba(0, 0, 0, 0.15);
}
@media (min-width: 769px) {
	.box {
		padding: 2.5rem 2.5rem 0;
		border-radius: 0.5rem;
	}
}
.box-footer {
	padding: 1rem 2rem;
	margin-inline: -2rem;
	border-top: 2px solid var(--meta-bg);
}
@media (min-width: 769px) {
	.box-footer {
		padding-inline: 2.5rem;
		margin-inline: -2.5rem;
	}
}
.post .box-footer {
	display: grid;
	align-items: center;
	grid-template-columns: 1fr auto;
	column-gap: 1.5rem;
}
.post .box-footer p {
	margin-block: 0;
}
.panel {
	gap: 2rem;
}
.panel-input {
	all: unset;
	display: block;
	width: 100%;
	max-height: 480px;
	field-sizing: content;
}
.panel-input:is(:hover, :focus-visible){
	background-color: var(--meta-bg);
	padding-inline: 0.25em;
	margin-inline: -0.25em;
	border-radius: 0.5rem;
	outline: 0.25em solid var(--meta-bg);
}
#title {
	color: var(--interactive);
	font-size: 1.25em;
	font-weight: bold;
	line-height: 1.2;
}
#comment {
	font-family: var(--mono);
	white-space-collapse: preserve;
	resize: none;
}
.label {
	background-color: Canvas;
	font-size: 0.85em;
	display: inline-flex;
	padding-inline: 0.5rem;
	border: var(--border);
	border-radius: 0.5rem;
}
.panel .box-footer {
	display: grid;
	gap: 1.5rem
}
.panel .row {
	justify-content: end;
}
@media (min-width: 769px) {
	.panel .box-footer {
		grid-template-columns: 1fr auto;
		grid-template-areas: 'meta actions';
		align-items: center;
	}
	.panel .row {
		grid-area: actions;
	}
}
.footer {
	display: grid;
	gap: 2rem 4rem;
	padding-block-start: 2rem;
}
.footer .meta {
	text-align: center;
}
.menu {
	justify-content: end;
}
@media (min-width: 769px) {
	.footer {
		grid-template-columns: 1fr auto;
		grid-template-areas:
			'nav menu'
			'meta meta';
	}
	.footer .meta {
		grid-area: meta;
	}
	nav {
		grid-area: nav;
	}
	.menu {
		grid-area: menu;
	}
}
EOD); setFile(Sys::$js, <<< 'EOD'
if (window.history.replaceState) {
	window.history.replaceState(null, null, window.location.href);
}

const $panel = document.querySelector('.panel');
const $button = $panel.querySelector('.delete');
if ($panel && $button) {
	const warning = $button.dataset.warning;
	$panel.addEventListener('submit', (e) => {
		if (e.submitter == $button) {
			if (confirm(warning)) {
				$panel.submit();
			} else {
				e.preventDefault();
			}
		}
	});
}
EOD);

	// Config setup
	if (!file_exists($configPath)) {
		setConfig(Sys::$settings);
	}
	if (!isGet('config') && isLoggedin()) {
		header('Location: /?config');
	}
}

if (file_exists($configPath)) {
	setConfigConstants();
}

// Database
function createFolder($folder) {
	$folder = sanitizeKey($folder);
	if (!folderExists($folder)) {
		mkdir(Sys::$folder.$folder);
	}
}

function setFile($name, $content) {
	file_put_contents(Sys::$folder.$name, $content);
}

function setConfig($content) {
	global $configPath;
	file_put_contents($configPath, json_encode($content));
	chmod($configPath, 0600);
}

function getConfig($query = false, $count = false) {
	global $configPath;

	if (file_exists($configPath)) {
		$config = json_decode((file_get_contents($configPath)));
		$configVars = get_object_vars($config);

		if (!$query) {
			return $config;
		} elseif ($query == 'key') {
			return array_keys($configVars)[$count - 1];
		} elseif ($query == 'value') {
			return array_values($configVars)[$count - 1];
		}
	}
}

function setConfigConstants() {
	$config = getConfig();
	$i = 0;
	foreach ($config as $key => $value) {
		$i++;
		define(getConfig('key', $i), getConfig('value', $i));
	}
}

function setPost($id, $content) {
	global $postsPath;
	$file = $postsPath.$id.'.json';
	file_put_contents($file, json_encode($content));
	chmod($file, 0600);
	setIndex();
}

function getPost($id, $value = false) {
	global $postsPath;

	if (isset($id)) {
		if (!str_ends_with($id, '.json')) {
			$id = $id.'.json';
		}
		$file = $postsPath.$id;

		if (file_exists($file)) {
			if (!$value) {
				return file_get_contents($file);
			} else {
				return json_decode((file_get_contents($file)))->$value;
			}
		}
	}
}

function getPostId($id) {
	if (str_ends_with($id, '.json')) {
		$id = substr($id, 0, -5);
	}
	return $id;
}

function getPostsCount() {
	global $postsPath;
	$posts = array_slice(scandir($postsPath), 2);;
	$count = count($posts);
	return $count;
}

function deletePost($id) {
	global $postsPath;
	$file = $postsPath.$id.'.json';
	unlink($file);
}

function postExists($id) {
	global $postsPath;
	return file_exists($postsPath.$id.'.json');
}

function setEntry($entry, $content) {
	global $dbPath;
	$file = $dbPath.$entry;
	file_put_contents($file, $content);
	chmod($file, 0600);
}

function getEntry($entry) {
	global $dbPath;
	$file = $dbPath.$entry;
	return file_exists($file) ? file_get_contents($file) : false;
}

function deleteEntry($entry) {
	global $dbPath;
	unlink($dbPath.$entry);
}

function folderExists($folder) {
	$folder = sanitizeKey($folder);
	return file_exists(Sys::$folder.$folder) && is_dir(Sys::$folder.$folder);
}

function sanitizeKey($text) {
	return preg_replace('/[^A-Za-z0-9_]/', '', $text);
}

function setIndex() {
	global $postsPath;
	$d = array();
	$h = opendir($postsPath);
	for($i = 0; ($e = readdir($h)) !== false; $i++) {
		if (str_ends_with($e, '.json')) {
			$d[$i]['key'] = $e;
			$d[$i]['value'] = getPost(getPostId($e), 'date');
			if ($d[$i]['value'] === false) {
				array_pop($d);
			}
		}
	}
	closedir($h);
	setEntry('index', serialize($d));
}

function getIndex() {
	return unserialize(getEntry('index'));
}

function getID(int $length) {
	return bin2hex(random_bytes($length));
}

// Cookie
function createCookie() {
	$identifier = getID('64');
	setEntry('cookie', $identifier);
	setcookie('vicco', $identifier, time()+(3600*24*30));
}

function deleteCookie() {
	deleteEntry('cookie');
	setcookie('vicco', '', time()-(3600*24*30));
}

// Status
function isLoggedin() {
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_COOKIE['vicco']) && $_COOKIE['vicco'] === getEntry('cookie')) {
		return true;
	}
}

function isGet($request) {
	if (isset($_GET[$request])) {
		return true;
	}
}

// Go to index
function returnHome() {
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
	$t = preg_replace('/\[([^\[]+)\]\(([^\)]+)\)/', '<a href=\'\2\' rel=\'external\' target=\'_blank\'>\1</a>', $t);
	$t = preg_replace('/\[(.*?)\]/', '<a href=\'\1\' rel=\'external\' target=\'_blank\'>\1</a>', $t);
	$t = '<p>'.$t.'</p>';
	$t = str_replace("\r\n\r\n", "</p><p>", $t);
	$t = str_replace("\n\n", "</p><p>", $t);
	$t = str_replace("\r\n", "<br>", $t);
	$t = str_replace("\n", "<br>", $t);
	$t = preg_replace('/<p>&gt;(.*?)<\/p>/', '<blockquote><p>\1</p></blockquote>', $t);

	return $t;
}

// Get posts
if (isGet('feed')) {
	$posts = @array_slice(getIndex(), 0, constant('postsFeed'));
} else {
	$posts = getIndex();
}

// Posts search
if (!empty($_GET['s'])) {
	$s = explode(' ', $_GET['s']);
	foreach ($posts as $postKey => $postValue) {
		$title = strtolower(getPost(getPostId($postValue['key']), 'title'));
		$comment = strtolower(parse(getPost(getPostId($postValue['key']), 'comment')));
		$f = true;
		for($i = 0; $i < sizeof($s); $i++) {
			if ((strpos($title, strtolower($s[$i])) === false) && strpos($comment, strtolower($s[$i])) === false) {
				$f = false;
				break;
			}
		}
		if (!$f) {
			unset($posts[$postKey]);
		}
	}
}
$results = sizeof($posts);
if (($results == 0) && isGet('s')) {
	error(L10n::$errorNoResults, true, '/', true);
}

// Posts sorting
uasort($posts, function($a, $b) {
	if ($a['value'] == $b['value']) {
		return 0;
	} else {
		return $b['value'] <=> $a['value'];
	}
});

// Get posts
if (isGet('p') && postExists($_GET['p'])) {
	$posts = array(array('value' => json_decode(getPost($_GET['p']))->date, 'key' => $_GET['p']));
}
$posts = @array_slice($posts, $_GET['skip'], constant('postsPerPage'));

// No posts exist
if (!$posts && !isGet('login') && !isLoggedin()) {
	error(L10n::$errorNoResults, false);
}

// Feed
if (isGet('feed')) {
	if (isGet('s')) {
		returnHome();
	}

	$dateFormat = 'Y-m-d\TH:i:sP';
	$lastUpdate = getPostId(reset($posts)['value']);
	header('Content-type: text/xml'); ?>
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title><?= constant('name') ?></title>
<?php if (!empty(constant('desc'))): ?>
<subtitle><?= constant('desc') ?></subtitle>
<?php endif ?>
<link href="<?= $blogUrl ?>" />
<link href="<?= $fullUrl ?>" rel="self"/>
<author>
	<name><?= constant('name') ?></name>
</author>
<updated><?= date($dateFormat, $lastUpdate) ?></updated>
<id><?= $fullUrl ?></id>
<?php foreach ($posts as $post) {
	$id = getPostId($post['key']);
	$draft = getPost($id, 'draft');
	if (!$draft): ?>
<entry>
	<title><?= getPost($id, 'title') ?></title>
	<link href="<?= $blogUrl.'/?p='.$id ?>" />
	<content type="html"><![CDATA[<?= parse(getPost($id, 'comment')) ?>]]></content>
	<updated><?= date($dateFormat, $post['value']) ?></updated>
	<id><?= $blogUrl.'/?p='.$id ?></id>
</entry>
<?php endif; } ?>
</feed><?php die();
}

// Header
function headerTpl() { ?>
<!DOCTYPE html><html lang="<?= constant('language') ?>"><head prefix="og: https://ogp.me/ns#">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="referrer" content="no-referrer">
	<meta property="og:type" content="<?= isGet('p') ? 'article' : 'website' ?>">

	<?php
		global $posts;

		if (isGet('p')) {
			$id = getPostId($_GET['p']);
		}
	?>
	<meta property="og:title" content="<?= isGet('p') ? getPost($id, 'title') : constant('name') ?>">

	<?php if (isGet('p')): ?>
		<?php
			$comment = getPost($id, 'comment');
			if ($comment) {
				$comment = strip_tags(parse($comment));
				$comment = str_replace(array(":"), ': ', $comment);
				$desc = strlen($comment) > 200 ? substr($comment, 0, 200)."…" : $comment;
			}
		?>
		<meta name="description" content="<?= $desc ?>">
		<meta property="og:description" content="<?= $desc ?>">
	<?php elseif (!empty(constant('desc'))): ?>
		<meta name="description" content="<?= constant('desc') ?>">
		<meta property="og:description" content="<?= constant('desc') ?>">
	<?php endif ?>

	<?php global $fullUrl; ?>
	<meta property="og:url" content="<?= $fullUrl ?>">

	<?php if (!empty(constant('fediverseCreator')) && isGet('p') && postExists($_GET['p'])): ?>
		<meta name="fediverse:creator" content="<?= constant('fediverseCreator') ?>">
	<?php endif ?>

	<title><?= constant('name') ?></title>

	<link href="/?feed" type="application/atom+xml" title="<?= constant('name') ?> feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="<?= Sys::$folder.Sys::$css ?>" media="screen">

	<?php if (!empty(constant('favicon'))): ?>
		<link rel="icon" href="data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20100%20100%22%3E%3Ctext%20y=%221em%22%20font-size=%2285%22%3E<?= constant('favicon') ?>%3C/text%3E%3C/svg%3E">
	<?php endif ?>
	<?php if (!empty(constant('mastodonVerification'))): ?>
		<link rel="me" href="<?= constant('mastodonVerification') ?>">
	<?php endif ?>

	<?php if (!empty(constant('customCSS'))): ?>
		<style><?= constant('customCSS') ?></style>
	<?php endif ?>

</head><body itemscope itemtype="https://schema.org/Blog">

<header class="header">
	<div>
		<h1 itemprop="name">
		<?php if (!empty($_GET)): ?>
			<a href="/"><?= constant('name') ?></a>
		<?php else: ?>
			<?= constant('name') ?>
		<?php endif ?>
		</h1>
	<?php if (!empty(constant('desc'))): ?>
		<p class="meta" itemprop="description"><?= constant('desc') ?></p>
	<?php endif ?>
	</div>

	<form class="search" action="/" method="get" role="search">
		<input type="search" name="s" aria-label="<?= L10n::$search ?>" placeholder="<?= L10n::$search ?>" value="<?= isGet('s') ? $_GET['s'] : '' ?>" required>
	</form>
</header><main>
<?php }

headerTpl();

// Error
function error($text, $backLink = true, $linkUrl = '/', $header = false) {
	if ($header) { headerTpl(); } ?>
	<section class="box">
		<h2><?= L10n::$error ?></h2>
		<div class="text">
			<p><?= $text ?>
		</div>
		<div class="box-footer">
			<?php if ($backLink): ?>
				<a class="button" href="<?= $linkUrl ?>"><?= L10n::$back ?></a>
			<?php endif ?>
		</div>
	</section>
<?php
	footerTpl();
	die();
}

// Login
if (isGet('login')) {
	if (isLoggedin()) {
		returnHome();
	} else { ?>
		<form class="box form" action="/" method="post">
			<h2><?= L10n::$login ?></h2>
			<div>
				<label for="username"><?= L10n::$username ?></label>
				<input type="text" id="username" name="username" autocomplete="username" required>
			</div>
			<div>
				<label for="passphrase"><?= L10n::$passphrase ?></label>
				<input type="password" id="passphrase" name="passphrase" autocomplete="current-password" required>
			</div>
			<div class="box-footer">
				<button type="submit" name="login"><?= L10n::$login ?></button>
			</div>
		</form>
	<?php
		footerTpl();
		die();
	}
}
if (isset($_POST['login'])) {
	if (hash_equals(constant('username'), $_POST['username']) && hash_equals(constant('passphrase'), $_POST['passphrase'])) {
		$_SESSION['loggedin'] = true;
		createCookie();
		returnHome();
	} else {
		error(L10n::$errorLogin, true, 'javascript:history.back()');
	}
}
if (isLoggedin()) {
	// Submit post
	if (isset($_POST['submit-post']) || isset($_POST['submit-draft'])) {
		if (empty($_POST['title']) || empty($_POST['comment'])) {
			error(L10n::$errorEmpty);
		}

		$post = new stdClass();
		$id = 0;

		if (empty($_POST['id'])) {
			$id = getID('6');
			$post->date = time();
		} else {
			if (!postExists($_POST['id'])) {
				error(L10n::$errorPostExists);
			}
			$id = $_POST['id'];
			$post->date = isset($_POST['submit-draft']) ? time() : getPost($id, 'date');
		}

		if (isset($_POST['submit-draft'])) {
			$post->draft = true;
		} else {
			$post->draft = false;
		}

		$post->title = htmlspecialchars($_POST['title']);
		$post->comment = htmlspecialchars($_POST['comment']);
		setPost($id, $post);
		returnHome();
	}

	// Delete post
	if (isset($_POST['delete-post'])) {
		deletePost($_POST['id']);
		setIndex();
		returnHome();
	}

	// Invalid post ID
	if (isGet('edit') && !postExists($_GET['edit'])) {
		error(L10n::$errorPostNonexistent);
	}

	// Post form
	if ((!(isGet('p')) && !isGet('s') && !isGet('config'))): ?>
		<form class="panel box" action="/" method="post">
			<input type="hidden" name="id" value="<?= (isGet('edit') ? $_GET['edit'] : '') ?>">

			<div>
				<input type="text" name="title" id="title" class="panel-input" required aria-label="<?= L10n::$title ?>" placeholder="<?= L10n::$title ?>" value="<?= (isGet('edit') ? getPost($_GET['edit'], 'title') : '') ?>">
			</div>

			<div>
				<textarea name="comment" id="comment" class="panel-input" spellcheck="false" rows="1" required aria-label="<?= L10n::$comment ?>" placeholder="<?= L10n::$placeholder ?>"><?= isGet('edit') ? getPost($_GET['edit'], 'comment') : '' ?></textarea>
			</div>

			<div class="box-footer">
				<?php if (isGet('edit')): ?>
					<p class="meta">
						<?php if (getPost($_GET['edit'], 'draft')): ?>
							<strong class="label"><?= L10n::$draft ?></strong>
						<?php endif ?>
						<?php $date = getPost($_GET['edit'], 'date'); ?>
						<time datetime="<?= date('Y-m-d H:i:s', $date) ?>" pubdate><?= date(constant('dateFormat'), $date) ?></time>
					</p>
				<?php endif ?>
				<div class="row">
					<button type="submit" name="submit-post"><?= L10n::$publish ?></button>
					<button type="submit" name="submit-draft"><?= L10n::$saveDraft ?></button>
					<?php if (isGet('edit')): ?>
						<button type="submit" class="delete" name="delete-post" data-warning="<?= L10n::$deleteWarning ?>"><?= L10n::$delete ?></button>
					<?php endif ?>
				</div>
			</div>
		</form>
	<?php endif;

	// Save config
	if (isset($_POST['submit-config'])) {
		$config = new stdClass();
		$i = 0;
		foreach ($_POST as $configKey => $configValue) {
			$i++;
			$key = getConfig('key', $i);
			$postName = explode('-', $configKey);
			if (!empty($_POST) && ($postName[0] == 'config')) {
				$config->$key = $configValue;
			}
		}
		setConfig($config);
		if (getEntry('installed') === false) {
			setEntry('installed', true);
		}
		header('Location: /?config');
	}

	// Config form
	if (isGet('config')): ?>
		<form class="box" action="/" method="post">
			<h2><?= L10n::$config ?></h2>
			<?= (getEntry('installed') === false) ? '<div class="text">'.parse(L10n::$setup).'</div>' : '' ?>
			<?php
				$config = getConfig();
				$i = 0;
				foreach ($config as $key => $value): $i++; ?>
					<div>
						<label for="config-<?= $i ?>"><?= getConfig('key', $i) ?></label>
						<input type="text" id="config-<?= $i ?>" name="config-<?= $i ?>" value="<?= $value ?>">
					</div>
			<?php endforeach ?>
			<div class="box-footer">
				<button type="submit" name="submit-config"><?= L10n::$save ?></button>
			</div>
		</form>
	<?php endif;

} elseif (isset($_POST['submit-post']) || isset($_POST['submit-draft']) || isset($_POST['submit-config']) || isset($_POST['delete-post']) || isGet('edit') || isGet('config')) {
	error(L10n::$errorHacker);
}

// Logout
if (isset($_POST['logout'])) {
	session_destroy();
	deleteCookie();
	returnHome();
}

// Posts
if (!isGet('edit') && !isGet('config')) {
	if (isGet('p') && empty($_GET['p'])) {
		error(L10n::$errorNoResults);
	}
	foreach ($posts as $post): ?>
		<?php
			$id = getPostId($post['key']);
			$postUrl = 'https://'.$_SERVER['HTTP_HOST'].'/?p='.$id;
			$title = getPost($id, 'title');
			$date = getPost($id, 'date');
			$comment = getPost($id, 'comment');
			$draft = getPost($id, 'draft');
		
			if ($draft && isLoggedIn()): ?>
				<article class="post box">
					<header class="post-header">
						<?php if (!isGet('p')): ?>
							<h2><a href="?p=<?= $id ?>"><?= $title ?></a></h2>
						<?php else: ?>
							<h2><?= $title ?></h2>
						<?php endif ?>
					</header>
					<?php if ($comment): ?>
						<div class="text"><?= parse($comment) ?></div>
					<?php endif ?>
					<footer class="box-footer">
						<p class="meta"><strong class="label"><?= L10n::$draft ?></strong> <time datetime="<?= date('Y-m-d H:i:s', $date) ?>"><?= date(constant('dateFormat'), $date) ?></time></p>
						<a class="button" href="?edit=<?= $id ?>"><?= L10n::$edit ?></a>
					</footer>
				</article>
			<?php elseif (!$draft): ?>
				<article class="post box" itemprop="blogPosts" itemscope itemtype="https://schema.org/BlogPosting" itemid="<?= $postUrl ?>">
					<header class="post-header">
						<?php if (!isGet('p')): ?>
							<h2 itemprop="name"><a href="?p=<?= $id ?>" itemprop="url"><?= $title ?></a></h2>
						<?php else: ?>
							<h2 itemprop="name"><?= $title ?></h2>
						<?php endif ?>
					</header>
					<?php if ($comment): ?>
						<div class="text" itemprop="articleBody"><?= parse($comment) ?></div>
					<?php endif ?>
					<footer class="box-footer">
						<p class="meta"><time datetime="<?= date('Y-m-d H:i:s', $date) ?>" itemprop="datePublished" pubdate><?= date(constant('dateFormat'), $date) ?></time></p>
						<?php if (isLoggedin()): ?>
							<a class="button" href="?edit=<?= $id ?>"><?= L10n::$edit ?></a>
						<?php endif ?>
					</footer>
				</article>
			<?php endif;
	endforeach;
}

// Footer
footerTpl($results);

function footerTpl($results = 0) { ?>
	</main><footer class="footer">
		<?php if (!isGet('p') && !isGet('edit') && $results >= constant('postsPerPage')): ?>
			<nav class="row">
				<?php if (@$_GET['skip'] > 0): ?>
					<a href="?skip=<?= (@$_GET['skip'] > 0 ? @$_GET['skip'] - constant('postsPerPage') : 0).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><span aria-hidden="true">&larr;</span> <?= L10n::$newer ?></a>
				<?php endif ?>
				<?php if (@$_GET['skip'] + constant('postsPerPage') < $results): ?>
					<a href="?skip=<?= (@$_GET['skip'] + constant('postsPerPage') < $results ? @$_GET['skip'] + constant('postsPerPage') : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><?= L10n::$older ?> <span aria-hidden="true">&rarr;</span></a>
				<?php endif ?>
			</nav>
		<?php endif ?>

		<div class="menu row">
			<?php if (constant('aboutText')): ?>
				<button popovertarget="info" popovertargetaction="show"><?= L10n::$about ?></button>
				<div id="info" class="box" popover>
					<h2><?= L10n::$about ?></h2>
					<div class="text">
						<?= parse(htmlspecialchars(constant('aboutText'))) ?>
						<p><button popovertarget="info" popovertargetaction="hide"><?= L10n::$close ?></button>
					</div>
				</div>
			<?php endif ?>
			<a class="button" href="/?feed"><?= L10n::$feed ?></a>
			<?php if (constant('showLogin') == true && !isGet('login') && !isLoggedin()): ?>
				<a class="button" href="?login"><?= L10n::$login ?></a>
			<?php elseif (isLoggedin()): ?>
				<a class="button" href="?config"><?= L10n::$config ?></a>
				<form action="/" method="post">
					<button type="submit" name="logout"><?= L10n::$logout ?></button>
				</form>
			<?php endif ?>
		</div>

		<?php if (isLoggedin()):
			$loadTime = number_format(rtrim(sprintf('%.20f', (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'])), '0'), 6, '.', ',');
			if (strpos(($loadTime.'0'), '0') != 0) {
				$loadTime = number_format($loadTime, 2, '.', ',');
			}?>
			<p class="meta"><?= getPostsCount() ?> / <?= $loadTime ?> s / <?= intval(memory_get_usage() / 1024) ?> KB
		<?php endif ?>
	</footer>

	<?php if (isLoggedin()): ?>
		<script src="<?= Sys::$folder.Sys::$js ?>"></script>
	<?php endif ?>
	</body></html>
<?php }

?>
