<?php     


/*
 * examples/mysql/loaddata.php
 * 
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
                              


/**
 * This script loads data from the database and returns it to the js
 *
 */
       
require_once('etc/config.php');      
require_once('functions/editable_grid.php');            

/**
 * fetch_pairs is a simple method that transforms a mysqli_result object in an array.
 * It will be used to generate possible values for some columns.
*/
function fetch_pairs($mysqli,$query){
	if (!($res = $mysqli->query($query)))return FALSE;
	$rows = array();
	while ($row = $res->fetch_assoc()) {
		$first = true;
		$key = $value = null;
		foreach ($row as $val) {
			if ($first) { $key = $val; $first = false; }
			else { $value = $val; break; } 
		}
		$rows[$key] = $value;
	}
	return $rows;
}


// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 
                    
// create a new EditableGrid object
$grid = new EditableGrid();

/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*  The third argument is the display type of grid
*/
/*
$grid->addColumn('id', 'ID', 'integer', NULL, false); 
$grid->addColumn('name', 'Name', 'string');  
$grid->addColumn('firstname', 'Firstname', 'string');  
$grid->addColumn('age', 'Age', 'integer');  
$grid->addColumn('height', 'Height', 'float');  
/* The column id_country and id_continent will show a list of all available countries and continents. So, we select all rows from the tables 
$grid->addColumn('id_continent', 'Continent', 'string' , fetch_pairs($mysqli,'SELECT id, name FROM continent'),true);  
$grid->addColumn('id_country', 'Country', 'string', fetch_pairs($mysqli,'SELECT id, name FROM country'),true );  
$grid->addColumn('email', 'Email', 'email');                                               
$grid->addColumn('freelance', 'Freelance', 'boolean');  
$grid->addColumn('lastvisit', 'Lastvisit', 'date');  
$grid->addColumn('website', 'Website', 'string');
*/
$grid->addColumn('id', 'ID', 'integer', NULL, false);
$grid->addColumn('WEEK', '星期', 'string');  

$grid->addColumn('COURSE_1_0', '铸造 [1.2节]', 'string');
$grid->addColumn('COURSE_1_1', '铸造 [3.4节]', 'string');
$grid->addColumn('COURSE_1_2', '铸造 [5.6节]', 'string');
$grid->addColumn('COURSE_1_3', '铸造 [7.8节]', 'string');
$grid->addColumn('COURSE_2_0', '锻压 [1.2节]', 'string');
$grid->addColumn('COURSE_2_1', '锻压 [3.4节]', 'string');
$grid->addColumn('COURSE_2_2', '锻压 [5.6节]', 'string');
$grid->addColumn('COURSE_2_3', '锻压 [7.8节]', 'string');
$grid->addColumn('COURSE_3_0', '焊接 [1.2节]', 'string');
$grid->addColumn('COURSE_3_1', '焊接 [3.4节]', 'string');
$grid->addColumn('COURSE_3_2', '焊接 [5.6节]', 'string');
$grid->addColumn('COURSE_3_3', '焊接 [7.8节]', 'string');
$grid->addColumn('COURSE_4_0', '车工 [1.2节]', 'string');          
$grid->addColumn('COURSE_4_1', '车工 [3.4节]', 'string');          
$grid->addColumn('COURSE_4_2', '车工 [5.6节]', 'string');          
$grid->addColumn('COURSE_4_3', '车工 [7.8节]', 'string');
$grid->addColumn('COURSE_5_0', '铣刨磨 [1.2节]', 'string');          
$grid->addColumn('COURSE_5_1', '铣刨磨 [3.4节]', 'string');          
$grid->addColumn('COURSE_5_2', '铣刨磨 [5.6节]', 'string');          
$grid->addColumn('COURSE_5_3', '铣刨磨 [7.8节]', 'string'); 
$grid->addColumn('COURSE_6_0', '数控 [1.2节]', 'string');          
$grid->addColumn('COURSE_6_1', '数控 [3.4节]', 'string');          
$grid->addColumn('COURSE_6_2', '数控 [5.6节]', 'string');          
$grid->addColumn('COURSE_6_3', '数控 [7.8节]', 'string'); 
$grid->addColumn('COURSE_7_0', '特加 [1.2节]', 'string');          
$grid->addColumn('COURSE_7_1', '特加 [3.4节]', 'string');          
$grid->addColumn('COURSE_7_2', '特加 [5.6节]', 'string');          
$grid->addColumn('COURSE_7_3', '特加 [7.8节]', 'string'); 
$grid->addColumn('COURSE_8_0', '钳工 [1.2节]', 'string');          
$grid->addColumn('COURSE_8_1', '钳工 [3.4节]', 'string');          
$grid->addColumn('COURSE_8_2', '钳工 [5.6节]', 'string');          
$grid->addColumn('COURSE_8_3', '钳工 [7.8节]', 'string'); 
$grid->addColumn('COURSE_9_0', '工艺设计 [1.2节]', 'string');          
$grid->addColumn('COURSE_9_1', '工艺设计 [3.4节]', 'string');          
$grid->addColumn('COURSE_9_2', '工艺设计 [5.6节]', 'string');          
$grid->addColumn('COURSE_9_3', '工艺设计 [7.8节]', 'string'); 
$grid->addColumn('COURSE_10_0', '考试 [1.2节]', 'string');          
$grid->addColumn('COURSE_10_1', '考试 [3.4节]', 'string');          
$grid->addColumn('COURSE_10_2', '考试 [5.6节]', 'string');          
$grid->addColumn('COURSE_10_3', '考试 [7.8节]', 'string');           

$result = $mysqli->query('SELECT * FROM total_schedule_2010_2011_1 WHERE SEMESTER_WEEK = 0 LIMIT 100');

$mysqli->close();

// send data to the browser
$grid->renderXML($result);

