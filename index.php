<?php

/*
	SPDX-License-Identifier: BSD-2-Clause
	SPDX-FileCopyrightText: Copyright (c) 2015–2024 lawl(†), zichy
*/

class Acc {
	static $username = 'admin'; // non-public
	static $passphrase = 'CHANGEME';
}

class Config {
	static $blogName = 'vicco';
	static $blogDesc = 'Yet another linkblog'; // optional
	static $logoPath = ''; // optional
	static $favicon = '🌱'; // optional
	static $language = 'en'; // (ISO 639-1)
	static $dateFormat = 'd M Y, H:i';
	static $mastodonVerification = 'https://mastodon.example/@account'; //optional
	static $fediverseCreator = '@account@mastodon.example'; //optional
	static $postsPerPage = 10;
	static $postsFeed = 20;
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

class L10n {
	static $search = 'Search';
	static $link = 'Link';
	static $title = 'Title';
	static $comment = 'Comment';
	static $optional = 'Optional';
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
	static $errorEmpty = 'Your post must contain a link and a title.';
	static $errorPostExists = 'A post with this ID already exists.';
	static $errorPostNonexistent = 'The post you wish to edit does not exist.';
	static $errorNoResults = 'No posts were found.';
	static $errorHacker = 'Nice try.';
	static $errorPermissions = 'No write permissions to create the folder ';
	static $introTitle = 'Welcome to vicco!';
	static $introComment = 'This is your new linkblog. Log in, have a look around and start posting.';
}

class Sys {
	static $path = 'vicco/';
	static $dbFolder = 'db/';
	static $postsFolder = 'posts/';
	static $css = 'style.css';
	static $js = 'script.js';
}

$dbPath = Sys::$path.Sys::$dbFolder;
$postsPath = Sys::$path.Sys::$postsFolder;

session_start();

// Installation
if(getEntry('installed') === false) {
	if(!folderExists('')) {
		if(!mkdir(Sys::$path)) {
			die(L10n::$errorPermissions.Sys::$path);
		}
	}
	createFolder(Sys::$dbFolder);
	createFolder(Sys::$postsFolder);
	setIndex();

	// Intro post
	if(!getIndex()) {
		$post = new stdClass();
		$id = getID('6');
		$post->date = time();
		$post->url = 'https://'.$_SERVER['HTTP_HOST'];
		$post->title = L10n::$introTitle;
		$post->comment = L10n::$introComment;
		setPost($id, $post);
	}

	setFile(Sys::$css, <<< 'EOD'
:root {
	--sans: system-ui, sans-serif;
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
	line-height: 1.2;
	margin: 0;
}
h1 {
	font-size: 1.25em;
	line-height: 1;
	margin-block: 0;
}
h1 :is(a, img) {
	display: block;
}
h1 a:hover {
	background-color: inherit;
}
h2 {
	font-size: 1.25em;
}
h2 a {
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
form {
	margin: 0;
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
	border: var(--border);
	border-radius: 0.5rem;
}
:is(input, textarea):focus-visible {
	border-color: var(--interactive);
	outline: 1px solid var(--interactive);
	outline-offset: 0;
}
input {
	width: 100%;
	height: 3.5rem;
	padding-inline: 1rem;
}
textarea {
	line-height: var(--line);
	display: block;
	width: 100%;
	padding: 1rem;
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
	align-items: center;
	justify-content: center;
	padding: 0.5rem 1rem;
	border: 0;
	border-radius: 0.5rem;
	cursor: pointer;
	touch-action: manipulation;
	user-select: none;
	-webkit-user-select: none;
}
:is(button, .button):is(:hover, :focus-visible) {
	background-color: var(--interactive);
	color: var(--box);
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
.header a {
	color: currentColor;
	text-decoration: none;
}
.header p {
	color: var(--meta);
	margin: 0;
}
.text > *:first-child {
	margin-block-start: 0;
}
.text > *:last-child {
	margin-block-end: 0;
}
.post {
	display: flex;
	flex-direction: column;
	row-gap: 1.5rem;
}
.box {
	background-color: var(--box);
	padding: 2rem 2.5rem;
}
@media (min-width: 769px) {
	.box {
		border-radius: 0.5rem;
	}
}
.meta {
	color: var(--meta);
}
hgroup > * {
	display: inline;
}
hgroup p:before {
	content: '(';
}
hgroup p:after {
	content: ')';
}
.permalink {
	color: currentColor;
	text-decoration: none;
	align-self: start;
}
.admin {
	align-items: start;
}
@media (max-width: 768px) {
	.panel {
		display: flex;
		flex-direction: column;
		row-gap: 2rem;
	}
}
@media (min-width: 769px) {
	.panel {
		display: grid;
		grid-template-areas:
			"link title"
			"comment comment"
			"submit submit";
		gap: 2rem;
	}
}
.panel-link {
	grid-area: link;
}
.panel-title {
	grid-area: title;
}
.panel-comment {
	grid-area: comment;
}
.footer {
	display: grid;
	grid-template-columns: 1fr auto;
	grid-template-areas: 'nav acc';
	grid-column-gap: 4rem;
	padding-block-start: 2rem;
}
nav {
	grid-area: nav;
}
.acc {
	grid-area: acc;
}
EOD
	);
	setFile(Sys::$js, <<< 'EOD'
if (window.history.replaceState) {
	window.history.replaceState(null, null, window.location.href);
}

const $textarea = document.getElementById('comment');
if($textarea) {
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
	); setEntry('installed', true);
}

// Database
function createFolder($folder) {
	$folder = sanitizeKey($folder);
	if(!folderExists($folder)) {
		mkdir(Sys::$path.$folder);
	}
}

function setFile($name, $content) {
	file_put_contents(Sys::$path.$name, $content);
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

	if(file_exists($file)) {
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
	return file_exists(Sys::$path.$folder) && is_dir(Sys::$path.$folder);
}

function sanitizeKey($k) {
	return preg_replace('/[^A-Za-z0-9_]/', '', $k);
}

function setIndex() {
	global $postsPath;
	$d = array();
	$h = opendir($postsPath);
	for($i = 0; ($e = readdir($h)) !== false; $i++) {
		if (str_ends_with($e, '.json')) {
			$d[$i]['key'] = $e;
			$d[$i]['value'] = getPost(getPostId($e), 'date');
			if($d[$i]['value'] === false) {
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
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_COOKIE['vicco']) && $_COOKIE['vicco'] === getEntry('cookie')) {
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
	$t = preg_replace('/\[([^\[]+)\]\(([^\)]+)\)/', '<a href=\'\2\' rel=\'external nofollow\' target=\'_blank\'>\1</a>', $t);
	$t = preg_replace('/\[(.*?)\]/', '<a href=\'\1\' rel=\'external nofollow\' target=\'_blank\'>\1</a>', $t);
	$t = '<p>'.$t.'</p>';
	$t = str_replace("\r\n\r\n", "</p><p>", $t);
	$t = str_replace("\n\n", "</p><p>", $t);
	$t = str_replace("\r\n", "<br>", $t);
	$t = str_replace("\n", "<br>", $t);
	return $t;
}

// Feed
if(isset($_GET['feed'])) {
	$posts = @array_slice(getIndex(), 0, Config::$postsFeed);
	uasort($posts, function($a, $b) {
		if($a['value'] == $b['value']) {
			return 0;
		} else {
			return $b['value'] <=> $a['value'];
		}
	});
	$lastUpdate = getPostId(reset($posts)['value']);
	$blogUrl = 'https://'.$_SERVER['HTTP_HOST'];
	$feedUrl = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('Content-type: application/atom+xml'); ?>
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title><?= Config::$blogName ?></title>
<?php if (!empty(Config::$blogDesc)): ?>
<subtitle><?= Config::$blogDesc ?></subtitle>
<?php endif ?>
<link href="<?= $blogUrl ?>" />
<link href="<?= $feedUrl ?>" rel="self"/>
<author>
	<name><?= Config::$blogName ?></name>
</author>
<updated><?= date('Y-m-d\TH:i:sP', $lastUpdate) ?></updated>
<id><?= $feedUrl ?></id>
<?php foreach($posts as $post): ?>
<?php $id = getPostId($post['key']); ?>
<entry>
	<title><?= getPost($id, 'title') ?></title>
	<link rel="alternate" type="text/html" href="<?= getPost($id, 'url') ?>" />
	<link rel="related" type="text/html" href="<?= $blogUrl.'/?p='.$id ?>" />
<?php if(getPost($id, 'comment')): ?>
	<content type="html"><![CDATA[<?= parse(getPost($id, 'comment')) ?>]]></content>
<?php endif ?>
	<updated><?= date('Y-m-d\TH:i:sP', $post['value']) ?></updated>
	<id><?= $blogUrl.'/?p='.$id ?></id>
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
	<?php if(!empty(Config::$fediverseCreator) && isset($_GET['p']) && postExists($_GET['p'])): ?>
		<meta name="fediverse:creator" content="<?= Config::$fediverseCreator ?>">
	<?php endif ?>

	<title><?= Config::$blogName ?></title>

	<link href="/?feed" type="application/atom+xml" title="<?= Config::$blogName ?> feed" rel="alternate">
	<link rel="stylesheet" type="text/css" href="<?= Sys::$path.Sys::$css ?>" media="screen">

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
		<?php $title = empty(Config::$logoPath) ? Config::$blogName : '<img src="'.Config::$logoPath.'" alt="'.Config::$blogName.'" itemprop="image">'; ?>
		<?= empty(Config::$logoPath) ? '<h1 itemprop="name">' : '<h1><meta itemprop="name" content="'.Config::$blogName.'">' ?>
		<?php if (!empty($_GET)): ?>
			<a href="/"><?= $title ?></a>
		<?php else: ?>
			<?= $title ?>
		<?php endif ?>
		</h1>
	<?php if (!empty(Config::$blogDesc)): ?>
		<p itemprop="description"><?= Config::$blogDesc ?></p>
	<?php endif ?>
	</div>

	<form class="search" action="/" method="get" role="search">
		<input type="search" name="s" aria-label="<?= L10n::$search ?>" placeholder="<?= L10n::$search ?>" required>
	</form>
</header><main>
<?php

// Footer template
function footer($results = 0) { ?>
	</main><footer class="footer">
		<?php if(!isset($_GET['p']) && !isEditing() && $results >= Config::$postsPerPage): ?>
			<nav class="row">
				<?php if (@$_GET['skip'] > 0): ?>
					<a href="?skip=<?= (@$_GET['skip'] > 0 ? @$_GET['skip'] - Config::$postsPerPage : 0).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><span aria-hidden="true">&larr;</span> <?= L10n::$newer ?></a>
				<?php endif ?>
				<?php if (@$_GET['skip'] + Config::$postsPerPage < $results): ?>
					<a href="?skip=<?= (@$_GET['skip'] + Config::$postsPerPage < $results ? @$_GET['skip'] + Config::$postsPerPage : @(int)$_GET['skip']).'&amp;s='.@urlencode($_GET['s']) ?>" class="button"><?= L10n::$older ?> <span aria-hidden="true">&rarr;</span></a>
				<?php endif ?>
			</nav>
		<?php endif ?>

		<div class="acc">
			<?php if(Config::$showLogin && !isset($_GET['login']) && !isLoggedin()): ?>
				<a class="button" href="?login">Login</a>
			<?php elseif(isLoggedin()): ?>
				<form action="/" method="post">
					<button type="submit" name="logout"><?= L10n::$logout ?></button>
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
function error($text, $backLink = true, $linkUrl = '/') { ?>
	<section class="box text">
		<h2><?= L10n::$error ?></h2>
		<p><?= $text ?>
		<?php if($backLink): ?>
			<p><a class="button" href="<?= $linkUrl ?>"><?= L10n::$back ?></a>
		<?php endif ?>
	</section>
<?php
	footer();
	die();
}

// Login
if(isset($_GET['login'])) {
	if(isLoggedin()) {
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
		footer();
		die();
	}
}
if(isset($_POST['login'])) {
	if(hash_equals(Acc::$username, $_POST['username']) && hash_equals(Acc::$passphrase, $_POST['passphrase'])) {
		$_SESSION['loggedin'] = true;
		createCookie();
		returnHome();
	} else {
		error(L10n::$errorLogin, true, 'javascript:history.back()');
	}
}
if(isLoggedin()) {
	// Submit posts
	if(isset($_POST['submit'])) {
		if(empty($_POST['url']) || empty($_POST['title'])) {
			error(L10n::$errorEmpty);
		}

		$post = new stdClass();
		$id = 0;

		if(empty($_POST['id'])) {
			$id = getID('6');
			$post->date = time();
		} else {
			if(!postExists($_POST['id'])) {
				error(L10n::$errorPostExists);
			}
			$id = $_POST['id'];
			$post->date = getPost($id, 'date');
		}

		$post->url = $_POST['url'];
		$post->title = $_POST['title'];
		$post->comment = $_POST['comment'];
		setPost($id, $post);
	}

	// Delete posts
	if(isset($_POST['delete'])) {
		deletePost($_POST['id']);
		setIndex();
	}

	// Invalid post ID
	if (isEditing() && !postExists($_GET['edit'])) {
		error(L10n::$errorPostNonexistent);
	}

	// Post form
	if ((!(isset($_GET['p'])) && !isSearching())): ?>
		<form class="panel box" action="/" method="post">
			<div class="panel-link">
				<label for="url"><?= L10n::$link ?></label>
				<input type="url" id="url" name="url" placeholder="https://example.com" required value="<?= (isEditing() ? getPost($_GET['edit'], 'url') : '') ?>">
			</div>

			<div class="panel-title">
				<label for="title"><?= L10n::$title ?></label>
				<input type="text" id="title" name="title" required value="<?= (isEditing() ? getPost($_GET['edit'], 'title') : '') ?>">
			</div>

			<div class="panel-comment">
				<label for="comment"><?= L10n::$comment ?> <small class="meta">(<?= L10n::$optional ?>)</small></label>
				<textarea id="comment" name="comment" spellcheck="false" rows="1"><?= (isEditing() ? getPost($_GET['edit'], 'comment') : '') ?></textarea>
			</div>

			<div class="row">
				<input type="hidden" name="id" value="<?= (isEditing() ? $_GET['edit'] : '') ?>">
				<button type="submit" id="submit" name="submit"><?= (isEditing() ? L10n::$save : L10n::$publish) ?></button>
			</div>
		</form>
	<?php endif;

} elseif(isset($_POST['submit']) || isset($_POST['delete']) || isEditing()) {
	error(L10n::$errorHacker);
}

// Logout
if(isset($_POST['logout'])) {
	session_destroy();
	deleteCookie();
	returnHome();
}

// Posts
$posts = getIndex();

// Search
if(!empty($_GET['s'])) {
	$s = explode(' ', $_GET['s']);
	foreach($posts as $postKey => $postValue) {
		$url = strtolower(getPost(getPostId($postValue['key']), 'url'));
		$title = strtolower(getPost(getPostId($postValue['key']), 'title'));
		$comment = strtolower(parse(getPost(getPostId($postValue['key']), 'comment')));
		$f = true;
		for($i = 0; $i < sizeof($s); $i++) {
			if((strpos($url, strtolower($s[$i])) === false) && (strpos($title, strtolower($s[$i])) === false) && strpos($comment, strtolower($s[$i])) === false) {
				$f = false;
				break;
			}
		}
		if(!$f) {
			unset($posts[$postKey]);
		}
	}
}
$results = sizeof($posts);
if(($results == 0) && isSearching()) {
	error(L10n::$errorNoResults);
}

// Sorting
uasort($posts, function($a, $b) {
	if($a['value'] == $b['value']) {
		return 0;
	} else {
		return $b['value'] <=> $a['value'];
	}
});

// Get posts
if(isset($_GET['p']) && postExists($_GET['p'])) {
	$posts = array(array('value' => json_decode(getPost($_GET['p']))->date, 'key' => $_GET['p']));
}
$posts = @array_slice($posts, $_GET['skip'], Config::$postsPerPage);

// No posts exist
if(!$posts && !isLoggedin()) {
	error(L10n::$errorNoResults, false);
}

// Posts
if(!isEditing()) {
	if(isset($_GET['p']) && empty($_GET['p'])) {
		error(L10n::$errorNoResults);
	}
	foreach($posts as $post): ?>
		<?php
			$id = getPostId($post['key']);
			$postUrl = 'https://'.$_SERVER['HTTP_HOST'].'/?p='.$id;
		?>
		<article class="post box" itemscope itemtype="https://schema.org/BlogPosting" itemid="<?= $postUrl ?>">
			<header>
				<hgroup>
					<h2 itemprop="name"><a href="<?= getPost($id, 'url') ?>" rel="external nofollow" target="_blank" aria-describedby="<?= $id?>-url" itemprop="url"><?= getPost($id, 'title') ?></a></h2>
					<p class="meta" id="<?= $id?>-url"><?= parse_url(getPost($id, 'url'), PHP_URL_HOST) ?></p>
				</hgroup>
			</header>
			<?php if(getPost($id, 'comment')): ?>
				<div class="text" itemprop="articleBody">
					<?= parse(getPost($id, 'comment')) ?>
				</div>
			<?php endif ?>
			<footer class="row meta">
				<?php $time = "<span aria-hidden=\"true\">&#8984;</span> <time datetime=\"".date('Y-m-d H:i:s', getPost($id, 'date'))."\" itemprop=\"datePublished\" pubdate> ".date(Config::$dateFormat, getPost($id, 'date'))."</time>" ?>
				<?php if (!isset($_GET['p'])): ?>
					<a class="permalink" href="?p=<?= $id ?>" itemprop="url">
						<?= $time ?>
					</a>
				<?php else: ?>
					<span><?= $time ?></span>
				<?php endif ?>
				<?php if (isLoggedin()): ?>
					<form class="admin row" action="/" method="post" data-warning="<?= L10n::$deleteWarning ?>">
						<input type="hidden" name="id" value="<?= $id ?>">
						<a class="button" href="?edit=<?= $id ?>"><?= L10n::$edit ?></a>
						<button type="submit" class="delete" name="delete"><?= L10n::$delete ?></button>
					</form>
				<?php endif ?>
			</footer>
		</article>
	<?php endforeach;
}

// Footer
footer($results);

?>
