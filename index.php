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
	<link href="css/styles.css" rel="stylesheet">
	<script src="js/script.js"></script>
</head>
<body>
	<div class="container site">
		<h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span></h1>
		<?php
			echo '<nav>';
				echo '<ul class="nav nav-pills">';
				require 'admin/database.php';

				$db = Database::connect();
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$statement = $db->query('SELECT * FROM categories');
				$categories = $statement->fetchAll();

				foreach ($categories as $category) {
					if ($category['id'] == 1) {
						echo '<li role="presentation" class="active">
						<a href="#' . $category["id"] . '" data-toggle="tab">' . $category["name"] . '</a>
						</li>';
					} else {
						echo '<li role="presentation"><a href="#' . $category["id"] . '" data-toggle="tab">' . $category["name"] . '</a></li>';
					}
					
				}
				echo '</ul>';
			echo '</nav>';
		
			echo '<div class="tab-content">';
				foreach ($categories as $category) {
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$statement = $db->prepare('SELECT * FROM items WHERE items.category = ?');
					$statement->execute(array($category['id']));
					if ($category['id'] == 1)
						echo '<div class="tab-pane active" id="' . $category["id"] . '">';
					else 
						echo '<div class="tab-pane" id="' . $category["id"] . '">';
					echo '<div class="row">';
					while ($item = $statement->fetch()){
						echo '<div class="col-sm-6 col-md-4">';
							echo '<div class="thumbnail">';
								echo '<img src="images/' . $item["image"] . '" alt="...">';
								echo '<div class="price">' . number_format((float)$item["price"], 2, '.', '') . ' €</div>';
								echo '<div class="caption">';
									echo '<h4>' . $category["name"] . '</h4>';
									echo '<p>' . $item["description"] . '</p>';
									echo '<a href="panier/panier.php?action=ajout&amp;l='. $category["name"] . '&amp;q='. 1 .'&amp;p=' . number_format((float)$item["price"], 2, '.', '') . '" onclick="window.open(this.href, "", 
"toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350"); return false;" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander</a>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						
					}
					echo '</div>';
				echo '</div>';
				}
				Database::disconnect();
			echo '</div>';
		?>					
	</div>
</body>
</html>
<a href="panier.php?action=ajout&amp;l=LIBELLEPRODUIT&amp;q=QUANTITEPRODUIT&amp;p=PRIXPRODUIT" onclick="window.open(this.href, '', 
'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350'); return false;">Ajouter au panier</a>