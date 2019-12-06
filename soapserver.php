<?php
Class lista
{
    public function list($users)
{
    $dsn = 'mysql:host=localhost;dbname=multi_login';
    $username = 'root';
    $password = '';

    try{
        // connect to mysql
        $con = new PDO($dsn,$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (Exception $ex)
        {
            echo 'Nincs kapcsolat '.$ex->getMessage();
        }




$stmt = $con->prepare('SELECT * FROM users');
$stmt->execute();
$users = $stmt->fetchAll();

foreach ($users as $user)
{
    echo $user['id'].' - '.$user['username'].' - '.$user['user_type'].' - '.$user['email'].'<br>';
}
return $users;
}

} 

$options = array(
	"uri" => "http://localhost/zh/soapserver.php");
	$server = new SoapServer(null, $options);
	$server->setClass('lista');
	$server->handle();
    

?>