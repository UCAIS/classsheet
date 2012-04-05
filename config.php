<?php
/**
 *  global config include database connect and other base config;
 *  by:Linfcstmr
 *  110721
 * 
 */


// Database connect setting

/** MySQL database name */
$DB_NAME = ('chasssheetold');

/** MySQL user name */
$DB_USER = ('root');

/** MySQL user password */
$DB_PASSWORD = ('root');

/** MySQL location */
$DB_HOST = ('localhost');

/** MySQL charset */
$DB_CHARSET = ('utf8');

/** MySQL collate */
$DB_COLLATE = ('');

/** MySQL Connect valus */
$DBConnect = mysql_connect($DB_HOST,$DB_USER,$DB_PASSWORD);

//Database connect status
try{
    if($DBConnect){
        print "Database connect success.";
    }else{
        throw new exception ("Database connect faill.");
    }
}catch(exception $e){
    echo $e->getmessage();
}

//Create the database if database not exists

if(!mysql_select_db($DB_NAME, $DBConnect)){
mysql_query('CREATE DATABASE classsheet');
}
mysql_select_db($DB_NAME, $DBConnect) or die ('Could not select database');

// /////////////////////////////////////////////////////// - semesterSet

//Create the semesterSet table if not exists

$sql = "CREATE TABLE IF NOT EXISTS semester (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),semester varchar(15),part int,weekCount int,startYear int,startMonth int, startDay int)";

mysql_query($sql,$DBConnect);
?>



