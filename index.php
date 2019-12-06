<?php
	date_default_timezone_set('Europe/Budapest');

	//MINDEN OLDAL TETEJÉRE AMIT CSAK REGISZTRÁLT FELHASZNÁLÓ LÁTHAT EZZEL KELL KEZDENI 
	include('functions.php');
	if (!isLoggedIn()) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}
?>
<?php
	$conn = mysqli_connect('localhost','root','', 'commentsection');
	//komment feltöltés
	function setComments()
	{
		if(isset($_POST['commentSubmit']))
		{
			$conn = mysqli_connect('localhost','root','', 'commentsection');
			$uid = $_POST['uid'];
			$date = $_POST['date'];
			$message = $_POST['message'];

			$sql = "INSERT INTO comments (uid, date, message) 
			VALUES ('$uid','$date','$message')";

			$result = $conn->query($sql);

			if(!$conn)
			{
				die("Connection failed: ".mysqli_connect_error());
			}
		}
	}
	
	//komment lekérés
	function getComments($conn)
	{
		$sql = "SELECT * FROM comments";
		$result = $conn->query($sql);

		while($row = $result->fetch_assoc())
		{
			echo "<div class='comment-box'><p>";
			echo $row['uid']."<br>";
			echo $row['date']."<br>";
			echo nl2br($row['message']);
			echo "</p></div>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div>
		
	</div>
	<div class="header">
		<h2>Home Page</h2>
	</div>
	<div class="diva">
	<a class="a"  href="index.php">Kezdőlap és komment</a>
	<a class="a"  href="upload.php">Képfeltöltés</a>
	<a class='a'  href="contact.php">Contact</a>
	<a class='a'  href="php.html">A php nyelv</a>
	</div>
	<div class="content">
		<?php if (!isLoggedIn()) : ?>
			<a href="login.php">Login</a>
		<?php endif ?>
		
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['success']; 
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>
	
		<div class="profile_info">
			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="index.php?logout='1" style="color: red;">logout</a>
						
					</small>

				<?php endif ?>
			</div>
		</div>
		<div class=menü >
		
		<div id="content">

			<?php //képlekérés és kommentmező a kép alatt
				$db = mysqli_connect('localhost','root','','photos');
				$sql = "SELECT * FROM images";
				$result = mysqli_query($db, $sql);
				
				//ha van feltöltve valami akkor megjelenik
				while($row = mysqli_fetch_array($result))
				{
					echo "<div id='img_div'>";
					echo "<p>".$row['text']."</p>";
					echo "<img src='img/".$row['image']."'>";
					echo "</div>";
					echo "<form method='POST' action='".setComments()."'>";
					echo "<input type='hidden' name='uid' value='Anonymous'>";
					echo "<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>";
					echo "<textarea name='message'></textarea><br>";
					echo "<button name='commentSubmit' type='submit'>Comment</button>";
					echo "</form>";

					getComments($conn);
				}
			?>

		</div>
	</div>
</body>
</html>