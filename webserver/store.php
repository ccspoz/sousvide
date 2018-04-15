<?php
$servername = "localhost";
$username = 'username';
$password = 'password';
$dbname = "dbname";
$tblname = "tblname"

$value = $_GET['temp'];

 try
 {
     $DBcon = new PDO("mysql:host={$servername};dbname={$dbname}",$username,$password); 
     $DBcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }
 catch(PDOException $e)
 {
     echo "ERROR : ".$e->getMessage();
 }

if(isset($_GET['temp']))
{
 $temp = $_GET['temp'];
 $error = $_GET['error'];
 $duty = $_GET['duty'];
 $p = $_GET['p'];
 $i = $_GET['i'];
 $d = $_GET['d'];
 
 $stmt = $DBcon->prepare("INSERT INTO $tblname(temp, error, duty, p, i, d) VALUES(:temp, :error, :duty, :p, :i, :d)");
 
 $stmt->bindparam(':tblname', $tblname);
 $stmt->bindparam(':temp', $temp);
 $stmt->bindparam(':error', $error);
 $stmt->bindparam(':duty', $duty);
 $stmt->bindparam(':p', $p);
 $stmt->bindparam(':i', $i);
 $stmt->bindparam(':d', $d);
 
 $stmt->execute();

}
?>
