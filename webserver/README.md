The files in this folder provide a web interface for viewing data from the PID controller.

To configure the webserver you will need to have PHP enabled and create an SQL database.
This database should have one table with the following fields:
(Note that datetime is used as the primary key)

|Name|Type|Null|Default|
|---|---|---|---|
|datetime|timestamp|No|CURRENT_TIMESTAMP|
|temp|float|Yes|*NULL*|
|error|float|Yes|*NULL*|
|duty|float|Yes|*NULL*|
|p|float|Yes|*NULL*|
|i|float|Yes|*NULL*|
|d|float|Yes|*NULL*|


The files you will need on the server, and notes about them:

- **PIDlog.html**
  - The main webpage displaying google graphs of the data.
  - Has three separate graphs for temperature, PID internal state, and output state.
- **PIDStore[random].php**
  - A script called by the microcontroller, used to store data into the servers database.
  - Filename is appended with random characters (substitute your own), to reduce the risk of attackers calling it.
  - To-do: Implement authentication, rather than simply obscuring the filename.
- **PIDgetTempData.php**
  - A script called by PIDlog.html to return data for the temperature graph.
  - A second temperature is included for example purposes (e.g. for a goal temperature, or cascaded PID arrangement). You will need to add a field in the database and ammend the sousvide.py script to use it.
- **PIDgetPIDData.php**
  - A script called by PIDlog.html to return data for the internal PID state (each P, I, D term)
- **PIDgetDriveData.php**
  - A script called by PIDlog.html to return the output state. Displays error and heater power/duty cycle.
- **PID_log_download.php**
  - A script to download all graph data in CSV format, with the filename specified in the textbox.
- **PID_truncate_table.php**
  - A script to truncate the table of logged data  (I.E delete all data in the database). A password has to be entered to to run.
  - Note that the random characters and password hash must be changed in this file prior to use.
- **passwordhasher.php**
  - A short script to generate the hash of your password. Convenience feature. For more security, generate the hash locally.
  - Use it by replacing the random characters and entering your chosen password. Then upload it to the server and visit the url. The hash that is returned should be entered (along with the random characters you chose) into the PID_truncate_table.php file.
  - Delete after use so that your password is not stored on the server.

  
