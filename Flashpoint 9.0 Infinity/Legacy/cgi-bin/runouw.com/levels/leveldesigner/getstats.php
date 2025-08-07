<?php

$db = new PDO('sqlite:sm63.db');

$statement = $db->query("SELECT * FROM level WHERE id=" . $_POST['id']);
$levels = $statement->fetchAll();

foreach ($levels as $level) {
	$votedscore = $level['votedscore'];
	$voteddiff = $level['voteddiff'];
	$name = $level['name'];
	$author = $level['author'];
	$score = $level['score'];
	$difficulty = $level['difficulty'];
	$votes = $level['votes'];
	$votes1 = $level['votes1'];
	$votes2 = $level['votes2'];
	$code = $level['code'];
	$comment = html_entity_decode($level['comment'], ENT_QUOTES);

	echo "&votedscore=$votedscore&voteddiff=$voteddiff&name=$name&author=$author&score=$score&difficulty=$difficulty&votes=$votes&votes1=$votes1&votes2=$votes2&code=$code&comment=$comment";
}