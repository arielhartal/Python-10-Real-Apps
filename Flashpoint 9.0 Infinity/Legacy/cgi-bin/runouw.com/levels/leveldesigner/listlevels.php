<?php
$db = new PDO('sqlite:sm63.db');

$sort_options =
[
	'oldest' => ['field' => 'id', 'direction' => 'asc'],
	'newest' => ['field' => 'id', 'direction' => 'desc'],
	'name' => ['field' => 'name', 'direction' => 'asc'],
	'name2' => ['field' => 'name', 'direction' => 'desc'],
	'author' => ['field' => 'author', 'direction' => 'asc'],
	'author2' => ['field' => 'author', 'direction' => 'desc'],
	'worst' => ['field' => 'score', 'direction' => 'asc'],
	'best' => ['field' => 'score', 'direction' => 'desc']
];

$sort = $sort_options[trim($_POST['order'])];
$field = $sort['field'];
$direction = $sort['direction'];
$statement = $db->query("SELECT id, name, author, score FROM level ORDER BY " . $sort['field'] . " " . $sort['direction'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start']);
$result = $statement->fetchAll();

$return = [];
foreach ($result as $level) {
	$level['name'] = str_replace('|', ' ', $level['name']);
	$entry = [$level['id'], $level['name'], $level['author'], $level['score'], 0, 0, 0, 0];
	$return[] = implode(',', $entry);
}

$return = implode('|', $return) . '|';
$statement = $db->query("SELECT COUNT(*) FROM level");
$total = $statement->fetchAll()[0][0];
echo "&code=$return&total=$total";