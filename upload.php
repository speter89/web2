<?php
	$msg = "";

	//ha megnyomjuk az upload gombot
	if (isset($_POST['upload']))
	{
		//feltöltött kép helye
		$target = "img/".basename($_FILES['image']['name']);

		//adatbázis csatlakozás
		$db = mysqli_connect("localhost", "root", "", "photos");

		//adatok feldolgozása a formból
		$image = $_FILES['image']['name'];
		$text = $_POST['text'];

		$sql = "INSERT INTO images (image, text) VALUES ('$image', '$text')";
		mysqli_query($db, $sql); //elmenti a beküldött adatokat az adatbázistáblába: images

		if(move_uploaded_file($_FILES['image']['tmp_name'], $target))
		{
			$msg = "Image uploaded successfully";
		}
		else
		{
			$msg = "Error";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Upload</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Upload</h2>
	</div>
	<div class="diva">
	<a class="a"  href="index.php">Kezdőlap és komment</a>
	</div>
	<div id="content">
		
		<form method="post" action="upload.php" enctype="multipart/form-data">
			<input type="hidden" name="size" value="1000000">
			<div>
				<input type="file" name="image">
			</div>
			<div>
				<textarea cols="40" rows="4" name="text"
				placeholder="Say something about this image...">
				</textarea>
			</div>
			<div>
				<input type="submit" name="upload" value="Upload Image">
			</div>
			<div>
				<a href="index.php">Home page</a>
			</div>
		</form>
	</div>
</body>
</html>