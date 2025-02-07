<?php

/*
	SPDX-License-Identifier: BSD-2-Clause
	SPDX-FileCopyrightText: Copyright (c) 2015–2025 lawl(†), zichy
*/

class Acc {
	static $username = 'admin'; // Non-public
	static $passphrase = 'CHANGEME';
}

class Config {
	static $blogName = 'vicco';
	static $blogDesc = 'Yet another linkblog'; // Optional
	static $favicon = '🌱'; // Emoji, optional
	static $language = 'en'; // (ISO 639-1)
	static $dateFormat = 'd M Y, H:i';
	static $postsPerPage = 10;
	static $postsFeed = 20;
	static $mastodonVerification = ''; // https://mastodon.example/@account
	static $fediverseCreator = ''; // @account@mastodon.example
	static $showLogin = true;
}

class Color {
	static $background = '#eee';
	static $box = '#fff';
	static $text = '#000';
	static $meta = '#666';
	static $interactive = '#00f';
	static $accent = '#fe9';
}

class Info {
	static $title = 'About';
	static $content = <<< 'EOD'
Here you can add information about *your blog*, *yourself*, or **legal notes**.

You can even use paragraphs.
EOD;
}

class L10n {
	static $search = 'Search';
	static $link = 'External link';
	static $title = 'Title';
	static $comment = 'Comment';
	static $optional = 'Optional';
	static $publish = 'Publish';
	static $save = 'Save';
	static $logout = 'Logout';
	static $permalink = 'Permalink';
	static $edit = 'Edit';
	static $delete = 'Delete';
	static $deleteWarning = 'Do you really want to delete this post?';
	static $older = 'Older';
	static $newer = 'Newer';
	static $username = 'Username';
	static $passphrase = 'Passphrase';
	static $feed = 'Feed';
	static $login = 'Login';
	static $back = 'Go back';
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
}

class Sys {
	static $folder = 'vicco/';
	static $dbFolder = 'db/';
	static $postsFolder = 'posts/';
	static $css = 'style.css';
	static $js = 'script.js';
}

$blogUrl = 'https://'.$_SERVER['HTTP_HOST'];
$fullUrl = $blogUrl.$_SERVER['REQUEST_URI'];
$dbPath = Sys::$folder.Sys::$dbFolder;
$postsPath = Sys::$folder.Sys::$postsFolder;

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
		$post->title = L10n::$introTitle;
		$post->url = '';
		$post->comment = L10n::$introComment;
		setPost($id, $post);
	}

	// Create assets
	setFile(Sys::$css, <<< 'EOD'
:root {
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
}
*:focus-visible {
	outline: 2px solid var(--interactive);
	outline-offset: 2px;
}
body {
	background-color: var(--background);
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
	width: 500px;
	max-width: 100%;
	max-height: 90vh;
	position: fixed;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	margin: 0;
	border: 0;
	border-radius: 1rem;
}
[popover]:popover-open {
	display: grid !important;
}
[popover]::backdrop {
	background: rgb(0 0 0 / 75%);
}
main {
	display: grid;
	gap: 2rem;
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
h1 img {
	display: block;
}
:is(h1, h2) a {
	text-decoration: none;
}
label {
	display: block;
	padding-block-end: 0.5rem;
}
:is(code, input, button) {
	font-size: var(--size);
}
code {
	background-color: var(--background);
}
blockquote {
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
	background-color: var(--box);
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
	min-height: 3.5rem;
	padding: 0.45rem 1rem;
	resize: none;
}
:is(button, .button) {
	background-color: var(--interactive);
	color: var(--box);
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
		align-items: flex-end;
	}
}
.header .meta {
	margin-block: 0;
}
.box {
	background-color: var(--box);
	display: grid;
	gap: 1.5rem;
	padding: 2rem;
}
@media (min-width: 769px) {
	.box {
		padding: 2rem 2.5rem;
		border-radius: 0.5rem;
	}
}
.post {
	gap: 1.5rem;
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
.post-header p {
	display: inline-flex;
	column-gap: 0.5rem;
	margin-block: 0;
}
hgroup > * {
	display: inline;
}
.permalink {
	text-decoration: none;
}
.post-footer {
	display: grid;
	align-items: center;
	grid-template-columns: 1fr auto;
	column-gap: 1.5rem;
}
.post-footer p {
	margin-block: 0;
}
.panel {
	gap: 2rem;
}
.panel .row {
	justify-content: space-between;
}
@media (min-width: 769px) {
	.panel {
		display: grid;
		grid-template-areas:
			'title link'
			'comment comment'
			'buttons buttons';
	}
	.panel-title {
		grid-area: title;
	}
	.panel-link {
		grid-area: link;
	}
	.panel-comment {
		grid-area: comment;
	}
	.panel .row {
		grid-area: buttons;
	}
}
.footer {
	display: grid;
	gap: 2rem 4rem;
	padding-block-start: 2rem;
}
.footer .meta {
	text-align: center;
	margin-block-end: 0;
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
EOD
	);
	setFile(Sys::$js, <<< 'EOD'
if (window.history.replaceState) {
	window.history.replaceState(null, null, window.location.href);
}

const $textarea = document.getElementById('comment');
if ($textarea) {
	function resizeArea($el) {
		let heightLimit = 400;
		$el.style.height = '';
		$el.style.height = Math.min($el.scrollHeight, heightLimit) +2 + 'px';
	}
	resizeArea($textarea);
	$textarea.addEventListener('input', function(e){
		const $target = e.target || e.srcElement;
		resizeArea($target);
	});
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
EOD
	); setEntry('installed', true);
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

function setPost($id, $content) {
	global $postsPath;
	$file = $postsPath.$id.'.json';
	file_put_contents($file, json_encode($content));
	chmod($file, 0600);
	setIndex();
}

function getPost($id, $value = false) {
	global $postsPath;
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

function getPostId($id) {
	if (str_ends_with($id, '.json')) {
		$id = substr($id, 0, -5);
	}
	return $id;
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

function getID($length) {
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

function isDetail() {
	if (isset($_GET['p'])) {
		return true;
	}
}

function isFeed() {
	if (isset($_GET['feed'])) {
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

function isLogin() {
	if(isset($_GET['login'])) {
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
	$t = preg_replace('/<p>>(.*?)<\/p>/', '<blockquote><p>\1</p></blockquote>', $t);

	return $t;
}

// Get posts
if (isFeed()) {
	$posts = @array_slice(getIndex(), 0, Config::$postsFeed);
} else {
	$posts = getIndex();
}

// Posts search
if (!empty($_GET['s'])) {
	$s = explode(' ', $_GET['s']);
	foreach($posts as $postKey => $postValue) {
		$url = strtolower(getPost(getPostId($postValue['key']), 'url'));
		$title = strtolower(getPost(getPostId($postValue['key']), 'title'));
		$comment = strtolower(parse(getPost(getPostId($postValue['key']), 'comment')));
		$f = true;
		for($i = 0; $i < sizeof($s); $i++) {
			if ((strpos($url, strtolower($s[$i])) === false) && (strpos($title, strtolower($s[$i])) === false) && strpos($comment, strtolower($s[$i])) === false) {
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
if (($results == 0) && isSearching()) {
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
if (isDetail() && postExists($_GET['p'])) {
	$posts = array(array('value' => json_decode(getPost($_GET['p']))->date, 'key' => $_GET['p']));
}
$posts = @array_slice($posts, $_GET['skip'], Config::$postsPerPage);

// No posts exist
if (!$posts && !isLogin() && !isLoggedin()) {
	error(L10n::$errorNoResults, false);
}

// Feed
if (isFeed()) {
	if (isSearching()) {
		returnHome();
	}

	$dateFormat = 'Y-m-d\TH:i:sP';
	$lastUpdate = getPostId(reset($posts)['value']);
	header('Content-type: text/xml'); ?>
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title><?= Config::$blogName ?></title>
<?php if (!empty(Config::$blogDesc)): ?>
<subtitle><?= Config::$blogDesc ?></subtitle>
<?php endif ?>
<link href="<?= $blogUrl ?>" />
<link href="<?= $fullUrl ?>" rel="self"/>
<author>
	<name><?= Config::$blogName ?></name>
</author>
<updated><?= date($dateFormat, $lastUpdate) ?></updated>
<id><?= $fullUrl ?></id>
<?php foreach($posts as $post): ?>
<?php $id = getPostId($post['key']); ?>
<entry>
	<title><?= getPost($id, 'title') ?></title>
<?php if (getPost($id, 'url')): ?>
	<link rel="alternate" type="text/html" href="<?= getPost($id, 'url') ?>" />
<?php endif ?>
	<link rel="related" type="text/html" href="<?= $blogUrl.'/?p='.$id ?>" />
	<content type="html"><![CDATA[<?= parse(getPost($id, 'comment')) ?>]]></content>
	<updated><?= date($dateFormat, $post['value']) ?></updated>
	<id><?= $blogUrl.'/?p='.$id ?></id>
</entry>
<?php endforeach ?>
</feed><?php die();
}

// Header
function headerTpl() { ?>
<!DOCTYPE html><html lang="<?= Config::$language ?>"><head prefix="og: https://ogp.me/ns#">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="referrer" content="no-referrer">
	<meta property="og:type" content="<?= isDetail() ? 'article' : 'website' ?>">

	<?php
		global $posts;

		if (isDetail()) {
			$id = getPostId($_GET['p']);
		}
	?>
	<meta property="og:title" content="<?= isDetail() ? getPost($id, 'title') : Config::$blogName ?>">

	<?php if (isDetail()): ?>
		<?php
			$comment = getPost($id, 'comment');
			if ($comment) {
				$comment = str_replace(array("\r", "\n"), '', $comment);
				$comment = str_replace(array(">"), ' ', $comment);
				$desc = strlen($comment) > 200 ? substr($comment, 0, 200)."…" : $comment;
			}
		?>
		<meta name="description" content="<?= $desc ?>">
		<meta property="og:description" content="<?= $desc ?>">
	<?php elseif (!empty(Config::$blogDesc)): ?>
		<meta name="description" content="<?= Config::$blogDesc ?>">
		<meta property="og:description" content="<?= Config::$blogDesc ?>">
	<?php endif ?>

	<?php global $fullUrl; ?>
	<meta property="og:url" content="<?= $fullUrl ?>">

	<?php if (!empty(Config::$fediverseCreator) && isDetail() && postExists($_GET['p'])): ?>
		<meta name="fediverse:creator" content="<?= Config::$fediverseCreator ?>">
	<?php endif ?>

	<title><?= Config::$blogName ?></title>

	<link href="/?feed" type="application/atom+xml" title="<?= Config::$blogName ?> feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="<?= Sys::$folder.Sys::$css ?>" media="screen">

	<?php if (!empty(Config::$favicon)): ?>
		<link rel="icon" href="data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20100%20100%22%3E%3Ctext%20y=%221em%22%20font-size=%2285%22%3E<?= Config::$favicon ?>%3C/text%3E%3C/svg%3E">
	<?php endif ?>
	<?php if (!empty(Config::$mastodonVerification)): ?>
		<link rel="me" href="<?= Config::$mastodonVerification ?>">
	<?php endif ?>

	<style>:root { --background: <?= Color::$background ?>; --box: <?= Color::$box ?>; --text: <?= Color::$text ?>; --meta: <?= Color::$meta ?>; --interactive: <?= Color::$interactive ?>; --accent: <?= Color::$accent ?>; }</style>

</head><body itemscope itemtype="https://schema.org/Blog">

<header class="header">
	<div>
		<h1 itemprop="name">
		<?php if (!empty($_GET)): ?>
			<a href="/"><?= Config::$blogName ?></a>
		<?php else: ?>
			<?= Config::$blogName ?>
		<?php endif ?>
		</h1>
	<?php if (!empty(Config::$blogDesc)): ?>
		<p class="meta" itemprop="description"><?= Config::$blogDesc ?></p>
	<?php endif ?>
	</div>

	<form class="search" action="/" method="get" role="search">
		<input type="search" name="s" aria-label="<?= L10n::$search ?>" placeholder="<?= L10n::$search ?>" required>
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
			<?php if ($backLink): ?>
				<p><a class="button" href="<?= $linkUrl ?>"><?= L10n::$back ?></a>
			<?php endif ?>
		</div>
	</section>
<?php
	footerTpl();
	die();
}

// Login
if (isLogin()) {
	if (isLoggedin()) {
		returnHome();
	} else { ?>
		<form class="box form" action="/" method="post">
			<div>
				<label for="username"><?= L10n::$username ?></label>
				<input type="text" id="username" name="username" autocomplete="username" required>
			</div>
			<div>
				<label for="passphrase"><?= L10n::$passphrase ?></label>
				<input type="password" id="passphrase" name="passphrase" autocomplete="current-password" required>
			</div>
			<div>
				<button type="submit" name="login"><?= L10n::$login ?></button>
			</div>
		</form>
	<?php
		footerTpl();
		die();
	}
}
if (isset($_POST['login'])) {
	if (hash_equals(Acc::$username, $_POST['username']) && hash_equals(Acc::$passphrase, $_POST['passphrase'])) {
		$_SESSION['loggedin'] = true;
		createCookie();
		returnHome();
	} else {
		error(L10n::$errorLogin, true, 'javascript:history.back()');
	}
}
if (isLoggedin()) {
	// Submit post
	if (isset($_POST['submit'])) {
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
			$post->date = getPost($id, 'date');
		}

		$post->title = $_POST['title'];
		$post->url = $_POST['url'];
		$post->comment = $_POST['comment'];
		setPost($id, $post);
		returnHome();
	}

	// Delete post
	if (isset($_POST['delete'])) {
		deletePost($_POST['id']);
		setIndex();
		returnHome();
	}

	// Invalid post ID
	if (isEditing() && !postExists($_GET['edit'])) {
		error(L10n::$errorPostNonexistent);
	}

	// Post form
	if ((!(isDetail()) && !isSearching())): ?>
		<form class="panel box" action="/" method="post">
			<input type="hidden" name="id" value="<?= (isEditing() ? $_GET['edit'] : '') ?>">

			<div class="panel-title">
				<label for="title"><?= L10n::$title ?></label>
				<input type="text" id="title" name="title" required value="<?= (isEditing() ? getPost($_GET['edit'], 'title') : '') ?>">
			</div>

			<div class="panel-link">
				<label for="url"><?= L10n::$link ?> <small class="meta">(<?= L10n::$optional ?>)</small></label>
				<input type="url" id="url" name="url" placeholder="https://example.com" value="<?= (isEditing() ? getPost($_GET['edit'], 'url') : '') ?>">
			</div>

			<div class="panel-comment">
				<label for="comment"><?= L10n::$comment ?></label>
				<textarea id="comment" name="comment" spellcheck="false" rows="1" required><?= (isEditing() ? getPost($_GET['edit'], 'comment') : '') ?></textarea>
			</div>

			<div class="row">
				<button type="submit" id="submit" name="submit"><?= (isEditing() ? L10n::$save : L10n::$publish) ?></button>
				<?php if (isEditing()): ?>
					<button type="submit" class="delete" name="delete" data-warning="<?= L10n::$deleteWarning ?>"><?= L10n::$delete ?></button>
				<?php endif ?>
			</div>
			
		</form>
	<?php endif;

} elseif (isset($_POST['submit']) || isset($_POST['delete']) || isEditing()) {
	error(L10n::$errorHacker);
}

// Logout
if (isset($_POST['logout'])) {
	session_destroy();
	deleteCookie();
	returnHome();
}

// Posts
if (!isEditing()) {
	if (isDetail() && empty($_GET['p'])) {
		error(L10n::$errorNoResults);
	}
	foreach($posts as $post): ?>
		<?php
			$id = getPostId($post['key']);
			$postUrl = 'https://'.$_SERVER['HTTP_HOST'].'/?p='.$id;
			$url = getPost($id, 'url');
			$title = getPost($id, 'title');
			$date = getPost($id, 'date');
			$comment = getPost($id, 'comment');
		?>
		<article class="post box" itemscope itemtype="https://schema.org/BlogPosting" itemid="<?= $postUrl ?>">
			<header class="post-header">
				<?php if ($url): ?>
					<hgroup>
						<h2 itemprop="name"><a href="<?= $url ?>" rel="external" target="_blank" aria-describedby="<?= $id?>-url" itemprop="url"><?= $title ?></a></h2>
						<p class="meta" id="<?= $id?>-url">(<?= parse_url($url, PHP_URL_HOST) ?>)</p>
					</hgroup>
				<?php elseif (!isDetail()): ?>
					<h2 itemprop="name"><a href="?p=<?= $id ?>" itemprop="url"><?= $title ?></a></h2>
				<?php else: ?>
					<h2 itemprop="name"><?= $title ?></h2>
				<?php endif ?>
			</header>
			<?php if ($comment): ?>
				<div class="text" itemprop="articleBody">
					<?= parse($comment) ?>
				</div>
			<?php endif ?>
			<footer class="post-footer">
				<p class="meta">
					<?php if ($url && !isDetail()): ?>
						<a href="?p=<?= $id ?>" class="permalink" title="<?= L10n::$permalink ?>" itemprop="url"><span aria-hidden="true">&#8984;</span></a>
					<?php endif ?>
					<time datetime="<?= date('Y-m-d H:i:s', $date) ?>" itemprop="datePublished" pubdate><?= date(Config::$dateFormat, $date) ?></time>
				</p>
				<?php if (isLoggedin()): ?>
					<a class="button" href="?edit=<?= $id ?>"><?= L10n::$edit ?></a>
				<?php endif ?>
			</footer>
		</article>
	<?php endforeach;
}

// Footer
footerTpl($results);

function footerTpl($results = 0) { ?>
	</main><footer class="footer">
		<?php if (!isDetail() && !isEditing() && $results >= Config::$postsPerPage): ?>
			<nav class="row">
				<?php if (@$_GET['skip'] > 0): ?>
					<a href="?skip=<?= (@$_GET['skip'] > 0 ? @$_GET['skip'] - Config::$postsPerPage : 0).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><span aria-hidden="true">&larr;</span> <?= L10n::$newer ?></a>
				<?php endif ?>
				<?php if (@$_GET['skip'] + Config::$postsPerPage < $results): ?>
					<a href="?skip=<?= (@$_GET['skip'] + Config::$postsPerPage < $results ? @$_GET['skip'] + Config::$postsPerPage : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><?= L10n::$older ?> <span aria-hidden="true">&rarr;</span></a>
				<?php endif ?>
			</nav>
		<?php endif ?>

		<div class="menu row">
			<?php if (Info::$title && Info::$content): ?>
				<button popovertarget="info" popovertargetaction="show"><?= Info::$title ?></button>
				<div id="info" class="box" popover>
					<div class="text">
						<h2><?= Info::$title ?></h2>
						<?= parse(Info::$content) ?>
					</div>
				</div>
			<?php endif ?>
			<a class="button" href="/?feed"><?= L10n::$feed ?></a>
			<?php if (Config::$showLogin && !isLogin() && !isLoggedin()): ?>
				<a class="button" href="?login">Login</a>
			<?php elseif (isLoggedin()): ?>
				<form action="/" method="post">
					<button type="submit" name="logout"><?= L10n::$logout ?></button>
				</form>
			<?php endif ?>
		</div>

		<?php if (isLoggedin()):
			$loadTime = number_format(rtrim(sprintf('%.20f', (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'])), '0'), 6, '.', ',');
			if (strpos(($loadTime.'0'), '0') != 0) {
			  $loadTime = number_format($loadTime, 2, '.', ',');
			} ?>
			<p class="meta"><a href="https://github.com/zichy/vicco">vicco</a> / <?= $loadTime ?> s / <?= intval(memory_get_usage() / 1024) ?> KB
		<?php endif ?>
	</footer>

	<?php if (isLoggedin()): ?>
		<script src="<?= Sys::$folder.Sys::$js ?>"></script>
	<?php endif ?>
	</body></html>
<?php }

?>
