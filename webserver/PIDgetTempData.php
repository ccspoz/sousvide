<?php

    $username = 'your_SQL_username';
    $password = 'your_SQL_password';
    $dbname = 'your_database_name';
    $tblname = 'your_table_name';
    $GMToffset = "-10 hours"; //Difference between server and local time

    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //specify time period we want to display, example shows a window 72 hours into the past (with +10h time difference between server and local time)
        $endDate = date("Y-m-d H:m:s", strtotime("+48 hours")); 
        $startDate = date("Y-m-d H:m:s", strtotime("-62 hours"));
        $result = $conn->query('SELECT datetime, temp, temp2, temp3, humidity FROM '.$tblname.' WHERE datetime BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
        
        // To get all data instead use:
        //$result = $conn->query('SELECT datetime, temp, temp2, temp3, humidity FROM '.$tblname.'');
        
        $rows = array();
        $table = array();
        $table['cols'] = array(

            // Chart labels, these represent the column titles.
            // Date is stored in format '2017-01-18 00:58:54' but google chart object wants 'Y/m/d H:i'

            array('label' => 'Time', 'type' => 'date'),
            array('label' => 'Chamber', 'type' => 'number'),
            array('label' => 'Heater', 'type' => 'number'),
            array('label' => 'Heater Goal', 'type' => 'number'),
            array('label' => 'Humidity', 'type' => 'number'),

        );

        foreach($result as $r) {
            $temp = array();
            
        //Date
            // Convert server time to local time, ignoring daylight savings. Example server is 10h ahead of local time so we subtract 10h
            $localTime = strtotime($r['datetime'].$GMToffset);
            $temp[] = array('v' => 'Date('.date('Y',$localTime).',' . 
                                          (date('n',$localTime) - 1).','.
                                           date('d',$localTime).','.
                                           date('H',$localTime).','.
                                           date('i',$localTime).','.
                                           date('s',$localTime).')');
        //Temp1 - chamber
            $temp[] = array('v' => $r['temp2']); 
        
        //Temp2 - heater
            $temp[] = array('v' => $r['temp']);        
        
        //Temp3 - heater goal
            $temp[] = array('v' => $r['temp3']);

        //Humidity
            $temp[] = array('v' => $r['humidity']);

            $rows[] = array('c' => $temp);
        }

        $table['rows'] = $rows;
        //print_r($table);

        // convert data into JSON format*/
        $jsonTable = json_encode($table);
        echo $jsonTable;
    }
    
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage(); 
    }

?>