<?php
    $username = 'your_SQL_username';
    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage(); 
    }
if(isset($_POST['delete'])){
    if(!empty($_POST['password'])) {
        if(md5('?Gd$w9U='.sha1('kRw+3+$D'.$_POST['password']) ) =='your_pasword_hash' ) {
            $result = $conn->query('TRUNCATE TABLE `'.$tblname.'`');
            echo 'Successfully truncated logtable!';
            $url='PIDlog.html';
            echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
        }
        else {
            echo 'Wrong password!';
            $url='PIDlog.html';
            echo '<META HTTP-EQUIV=REFRESH CONTENT="2; '.$url.'">';
        }
    }
    else {
        echo 'Empty password!';
        $url='PIDlog.html';
        echo '<META HTTP-EQUIV=REFRESH CONTENT="2; '.$url.'">';
    }
}        
else {
    echo 'Truncate Failed.';
    $url='PIDlog.html';
    echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
}
?>