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

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.css"/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.js"></script>
	<link href="../css/styles.css" rel="stylesheet">
	<script src="../js/script.js"></script>
</head>
<body>
	<div class="container" style="margin-top: 50px;">
	<?php
		if (!empty($_GET['message'])) {
			echo '<div class="alert alert-success" role="alert">';
		        echo $_GET['message'];
		        echo '<button type="button" class="close" data-dismiss="alert">';
		            echo '<span>&times;</span>';
		        echo '</button>';
		    echo '</div>';
		}
	?>
	</div>
	<h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span></h1>
	<div class="container admin">
		<h1><strong>Liste des items   </strong><a href="insert.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span> Ajouter</a></h1>
		<table id="table_id" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Description</th>
					<th>Prix</th>
					<th>Categorie</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					require 'database.php';
					$db = Database::connect();
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$statement = $db->query('SELECT items.id, items.name, items.description, items.price, categories.name AS category FROM items LEFT JOIN categories ON items.category = categories.id ORDER BY items.id DESC');
					while ($item = $statement->fetch()) {
						echo '<tr>';
							echo '<td>' . $item["name"] . '</td>';
							echo '<td>' . $item["description"] . '</td>';
							echo '<td>' . number_format((float)$item["price"], 2, '.', '') . ' €</td>';
							echo '<td>' . $item["category"] . '</td>';
							echo '<td width="300">';
								echo '<a href="view.php?id=' . $item["id"] . '" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
								echo ' ';
								echo '<a href="update.php?id=' . $item["id"] . '" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
								echo ' ';
								echo '<a href="delete.php?id=' . $item["id"] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer</a>';
							echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				
			</tbody>
		</table>
	</div>
</body>
</html>