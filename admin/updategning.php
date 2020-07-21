<?php 
	require 'database.php';
	
	if (!empty($_GET['id'])) {
		$id = checkInput($_GET['id']);
	}

	$nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $descripion = $price = $category = $image = ""; 

	if (!empty($_POST)) {
		$name = checkInput($_POST['name']);
		$description = checkInput($_POST['description']);
		$price = checkInput($_POST['price']);
		$category = checkInput($_POST['category']);
		$image = checkInput($_FILES['image']['name']);
		$imagePath = '../images/' . basename($image);
		$imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
		$isSuccess = true;

		if (empty($name)) {
			$nameError = 'Ce champ ne peut pas etre vide';
			$isSuccess = false;
		}
		if (empty($description)) {
			$descriptionError = 'Ce champ ne peut pas etre vide';
			$isSuccess = false;
		}
		if (empty($price)) {
			$priceError = 'Ce champ ne peut pas etre vide';
			$isSuccess = false;
		}
		if (empty($category)) {
			$categoryError = 'Ce champ ne peut pas etre vide';
			$isSuccess = false;
		}
		if (empty($image)) {
			$isImageUdated = false;
		} else {
			$isImageUdated = true;
			$isUploadSeccess = true;
			if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
				$imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
				$isUploadSeccess = false;
			}
			if (file_exists($imagePath)) {
				$imageError = "Le fichier existe deja";
				$isUploadSeccess = false;
			}
			if ($_FILES["image"]["size"] > 500000) {
				$imageError = "Le fichier ne doit pas depasser les 500KB";
				$isUploadSeccess = false;
			}
			if ($isUploadSeccess) {
				if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
					$imageError = "Il y'a eu une erreur lors de l'upload";
					$isUploadSeccess = false;
				} 
			}
		}
		If (($isSuccess && $isImageUdated && $isUploadSeccess) || ($isSuccess && !$isImageUdated)) {
			$db = Database::connect();
			if ($isImageUdated) {
				$statement = $db->prepare("UPDATE items SET name=?, description=?, price=?, category=?, image=? WHERE id=?");
				$statement->execute(array($name, $description, $price, $category, $image, $id));
			} else {
				$statement = $db->prepare("UPDATE items SET name=?, description=?, price=?, category=? WHERE id=?");
				$statement->execute(array($name, $description, $price, $category, $id));
			}
			
			Database::disconnect();
			header("Location: index.php?message=updated");
		} elseif ($isImageUdated && !$isUploadSeccess) {
			$db = Database::connect();
			$statement = $db->prepare('SELECT * FROM items WHERE items.id = ?');
			$statement->execute(array($id));
			$item 			= $statement->fetch();
			$image = $item['image'];
			Database::disconnect();
		}

	} else {
		$db = Database::connect();
		$statement = $db->prepare('SELECT * FROM items WHERE items.id = ?');
		$statement->execute(array($id));
		$item 			= $statement->fetch();
		$name 			= $item['name'];
		$description 	= $item['description'];
		$price 			= $item['price'];
		$category 		= $item['category'];
		$image 			= $item['image'];
		Database::disconnect();
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
		<h1><strong>Modifier un item</strong></h1>
		<br>
		<div class="row">
			<div class="col-sm-6">
				<form class="form" role="form" action="<?php echo 'update.php?id=' . $id; ?>"  method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label for="name">Nom:</label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Nom" value="<?php echo $name; ?>">
						<span class="help-inline"><?php echo $nameError; ?></span>
					</div>
					<div class="form-group">
						<label for="description">Description:</label>
						<input type="text" class="form-control" name="description" id="description" placeholder="Description" value="<?php echo $description; ?>">
						<span class="help-inline"><?php echo $descriptionError; ?></span>
					</div>
					<div class="form-group">
						<label for="price">Prix: (en €)</label>
						<input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Prix" value="<?php echo $price; ?>">
						<span class="help-inline"><?php echo $priceError; ?></span>
					</div>
					<div class="form-group">
						<label for="category">Category:</label>
						<select class="form-control" id="category" name="category">
							<option value="">--Sectionner un category--</option>
							<?php
								$db = Database::connect();
								foreach ($db->query('SELECT * FROM categories') as $row) {
									if ($row['id'] == $category) {
										echo '<option selected value="' . $row['id'] . '">' . $row['name'] . '</option>';
									} else {
										echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
									}
								}
								Database::disconnect();
							?>
						</select>
						<span class="help-inline"><?php echo $categoryError; ?></span>
					</div>
					<div class="form-group">
						<label>Image:</label>
						<p><?php echo $image; ?></p>
						<label for="image">Selectionner une image:</label>
						<input type="file" name="image" id="image">
						<span class="help-inline"><?php echo $imageError; ?></span>
					</div>	
					<br>
					<div class="form-actions">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
						<a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
					</div>
				</form>
			</div>
			<div class="col-sm-6 site">
				<div class="thumbnail">
					<img src="../images/<?php echo $image; ?>" alt="...">
					<div class="price"><?php echo number_format((float)$price, 2, '.', '') . "€"; ?></div>
					<div class="caption">
						<h4><?php echo $name; ?></h4>
						<p><?php echo " " . $description; ?></p>
						<a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>