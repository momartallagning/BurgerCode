<?php 
	require 'database.php';

	if (!empty($_GET['id'])) {
		$id = checkInput($_GET['id']);
	}

	if (!empty($_POST)) {
		$id = checkInput($_POST['id']);
		$db = Database::connect();
		$statement = $db->prepare('DELETE FROM items WHERE items.id = ?');
		$statement->execute(array($id));
		Database::disconnect();
		header("Location: index.php");
	}

	function checkInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Burger Code</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet">
	<link href="../css/styles.css" rel="stylesheet">
	<script src="../js/script.js"></script>
</head>
<body>
	<h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span></h1>
	<div class="container admin">
		<h1><strong>Supprimer un item</strong></h1>
		<br>
		<form class="form" role="form" action="delete.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-actions">
				<p class="alert alert-warning">Etes vous sur de vouloir supprimer ?</p>
				<button type="submit" class="btn btn-warning">Oui</button>
				<a class="btn btn-default" href="index.php">Non</a>
			</div>
		</form>
	</div>
</body>
</html>