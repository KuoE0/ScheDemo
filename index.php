<?php
/**
 * Short description for index.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package index
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';
include_once 'function.php';

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'title'";
$stmt = $db->prepare($sql);
$stmt->execute();

$title = $stmt->fetch()['value'];

?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	</head>
	<body>
		<div class="container">
			<h1><?php echo $title ?></h1>
			<div class="register">
				<form action="register.php" method="POST">
					<label>Group</label>
					<select name="group_id">
<?php
$sql = "SELECT `group_id` FROM `groups`";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	$id = $data_row['group_id'];
	$name_list = get_member_names($db, $id);
	echo '<option value=' . $id . '>' . $id . '. ' . implode('；', $name_list) . '</option>';

}

?>
					</select>

					<label>Time</label>
					<select name="time_id">
<?php
$sql = "SELECT * FROM `timeslots` WHERE `occupied` = '0'";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	var_dump($data_row);
	$id = $data_row['time_id'];
	$time = $data_row['begin'];
	$order = $data_row['slice'];
	echo '<option value=' . $id . '>' . $time . ' - ' . $order . '</option>';
}

?>
					</select>
					<label>Presentation Title</label>
					<input type="text" name="title" />
					<button class="btn btn-primary" type="submit">Register</button>


				</form>
			</div>

			<div class="timeslot">
				<table class="table table-striped">
					<caption>Presentation Order</caption>
					<tr>
						<th>Time</th>
						<th>Order</th>
						<th>Presenter</th>
						<th>Title</th>
					</tr>
<?php
$sql = "SELECT * FROM `timeslots` ORDER BY `begin` ASC, `slice` ASC";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	$time_id = $data_row['time_id'];
	$presentation_info = get_presentation_info_by_time_id($db, $time_id);
	$name_list = get_member_names($db, $presentation_info['group_id']);

	echo '<tr>';
	echo '<td>' . $data_row['begin'] . " ~ " . $data_row['end'] . '</td>';
	echo '<td>' . $data_row['slice'] . "</td>";
	echo '<td>' . implode('<br />', $name_list) . '</td>';
	echo '<td>' . $presentation_info['title'] . '</td>';
	echo '</tr>';

}


?>
				</table>
			</div>

		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
