<?php 
session_start();

//kapcsolódás az adatbázishoz
$db = mysqli_connect('localhost','root','', 'multi_login');


$username = "";
$email    = "";
$errors   = array(); 


if (isset($_POST['register_btn'])) {
	register();
}

//regisztráció funkció meghívása a regisztráció gomb megnyomásakor
function register(){
	// változók globálként meghívása hogy elérhetők legyenek a funkcióban
	global $db, $errors, $username, $email;
	//input értékek foadása a formból e() funkció meghívása

	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);
	//mezők megfelelő kitöltése
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	//felhasználó regisztrálása ha nincs error
	if (count($errors) == 0) {
		$password = md5($password_1);// jelszó kódolása mentés előtt

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			
			$logged_in_user_id = mysqli_insert_id($db);// id generálása a felhasználónak

			$_SESSION['user'] = getUserById($logged_in_user_id); //belépett felhasználó session-be tétele
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');				
		}
	}
}

// felhasználó id alapján azonosítása (id alapján ki szedi az összes változót és arrabe(tömbbe) tárolja)
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

//escape string: speciális karakterek szűrése
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}	
function isLoggedIn()//ellenőrzi hogy bevan-e jelentkezve a felhasználó
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}//kijelentkezés gomb 
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: index.php");
}// meghívja a login() függvényt a gomra kattintva
if (isset($_POST['login_btn'])) {
	login();
}

//bejelentkezés
function login(){
	global $db, $username, $errors;
	// változók kiszedése
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	//megfelelő kitöltés ellenőrzése
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	//beléptetés megkisérlése ha nincs error
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // ha megvan a felhasználó
			// ellenőrzi hogy admin vagy user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'Admin') {//ha admin ide külde>>admin.php

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;//ha user ide index.php
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}
function isAdmin()//ellenőrzi hogy a felhasználó admin-e
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'Admin' ) {
		return true;
	}else{
		return false;
	}
}