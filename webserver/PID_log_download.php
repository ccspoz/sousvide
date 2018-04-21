<?php

    if(isset($_POST['download'])){
        
        try {
            $username = 'your_SQL_username';
            $password = 'your_SQL_password';
            $dbname = 'your_database_name';
            $tblname = 'your_table_name';
            
            $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
           
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage(); 
        }

        $result = $conn->query('SELECT * FROM `'.$tblname.'`');
        if (!$result) die('Couldn\'t fetch records');

        $data = array();
        $fields = array_keys($result->fetch(PDO::FETCH_ASSOC));
        array_push($data, array_values($fields));
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, array_values($row));
        }

        $fp = fopen('php://output', 'w');
        if ($fp && $result) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$_POST['filename'].'.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            // fputcsv($fp, $headers);
            foreach($data as $rows){
                fputcsv($fp, array_values($rows));
            }
            die;
        }

    }
    
    else {
        echo 'Download Failed.';
    }
    
?>