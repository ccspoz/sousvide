<?php

    $username = 'username';
    $password = 'password';
    $dbname = 'dbname';
    $tblname = 'tblname';
    $GMToffset = "-10 Hours";

    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // To get all data use:
        //$result = $conn->query('SELECT * FROM $tblname');
        //$result->bindparam(':tblname', $tblname);
        
        $endDate = date("Y-m-d H:m:s", strtotime("+48 hours")); 
        $startDate = date("Y-m-d H:m:s", strtotime("-62 hours"));
        $result = $conn->query('SELECT * FROM $tblname WHERE datetime BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
        $result->bindparam(':tblname', $tblname);
        
        $rows = array();
        $table = array();
        $table['cols'] = array(

            // Chart labels, these represent the column titles.
            // Date is stored in format '2017-01-18 00:58:54' but google chart object wants 'Y/m/d H:i'

            array('label' => 'Time', 'type' => 'date'),
            array('label' => 'Temperature', 'type' => 'number'),
            array('label' => 'Style', 'type' => 'string', 'role' => 'style'),
            array('label' => 'Error', 'type' => 'number'),
            array('label' => 'Duty Cycle', 'type' => 'number'),
            array('label' => 'P', 'type' => 'number'),
            array('label' => 'I', 'type' => 'number'),
            array('label' => 'D', 'type' => 'number')

        );

        foreach($result as $r) {
            $temp = array();
            
        //Date
            // Convert to local time from server time, ignores changes to daylight savings
            $localTime = strtotime($r['datetime'].$GMToffset);
            $temp[] = array('v' => 'Date('.date('Y',$localTime).',' . 
                                          (date('n',$localTime) - 1).','.
                                           date('d',$localTime).','.
                                           date('H',$localTime).','.
                                           date('i',$localTime).','.
                                           date('s',$localTime).')');
        //Temp
            $temp[] = array('v' => (float) $r['temp']); 
        //Change line style depending on temperature for a visual warning when temp is of out of bounds
            if ($r['temp'] > 23.5 && $r['temp'] < 27.0) {
                $style = 'color:blue';
            }
            else if ($r['temp'] > 23.0 && $r['temp'] < 27.5){
                $style = 'color:DarkOrange';
            }
            else {
                $style = 'color:red';
            }
            $temp[] = array('v' => $style);
        
        //Other params
            $temp[] = array('v' => (float) $r['error']);
            $temp[] = array('v' => (float) $r['duty']);
            $temp[] = array('v' => (float) $r['p']);
            $temp[] = array('v' => (float) $r['i']);
            $temp[] = array('v' => (float) $r['d']);

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
