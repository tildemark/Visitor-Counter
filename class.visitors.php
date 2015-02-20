<?php

/*
	This SQL query will create the table to store your object.

	CREATE TABLE visitors (
	  `time` time NOT NULL default '00:00:00',
	  `ip` int(4) unsigned NOT NULL default '0'
	) ENGINE=MyISAM;";

*/


/**
* <b>Online Vistors Count</b> class.
* @author Alfredo Sanchez
* @version 1.0 / PHP4
* @see http://www.tildemark.com/programming/php
* @copyright Free for personal & commercial use. 
*/
class visitors 
{
	/**
	 * @var tinyint
	 */
	var $interval = 300;  // 5 minutes interval 

	/**
	 * @var tinyint
	 */
	var $visitors = 0;  // online visitors

	/**
	 * @var int
	 */
	var $hits = 0;  // online impressions

	/**
	 * Function that inserts the visitors IP address into the database. 
	 * If the IP address is a NULL value then assume its from the localhost.
	 * Returns the row id of the inserted data from the database.
	 *
	 * @return integer $id
	 */
	function insert()
	{
	    global $REMOTE_ADDR;
	    if($REMOTE_ADDR == NULL) $REMOTE_ADDR = "127.0.0.1";
	    $ip = ip2long($REMOTE_ADDR);
	
		$db = new Database();
		$connection = $db->Connect();
	    $query = "INSERT INTO `visitors` (time, ip) VALUES (NOW(), $ip)";
		$id = $db->InsertOrUpdate($query, $connection);
		return $id;
	}

	/**
	 * Function that deletes all entries that older than the $interval value. 
	 * Returns a boolean value base on the success of the deletion.
	 *
	 * @param none
	 * @return integer $id
	 */
	function clean()
	{
		$db = new database();
		$connection = $db->Connect();
	    $query = "DELETE FROM `visitors` WHERE time < DATE_SUB(NOW(), INTERVAL $this->interval SECOND)";
		return Database::InsertOrUpdate($query, $connection);
	}
	
	/**
	 * Function that displays the counted number of visitors from the database.
	 * Returns a numeric value
	 *
	 * @return integer $id
	 */
	function display($viewtype = NULL)
	{
	    if ($viewtype == NULL) $viewtype=0;
	
	    switch ($viewtype){
			case 1: //count hits
			    $query = "SELECT count(ip) as count FROM `visitors`";
			    $row = $this->update($query);
			    return $row["count"];
			break;
			case 2: //count visitors
			default:
			    $query = "SELECT count(DISTINCT ip) AS count FROM `visitors`";
			    $row = $this->update($query);
			    return $row["count"];		    
			break;    
		}
	} 
	
	/**
	 * A helper function that is used to update rows in the database. 
	 * Returns a numeric value
	 *
	 * @return integer $id
	 */
	function update($query)
	{
		$db = new database();
		$connection = $db->Connect();
		$this->clean();
		$cursor = mysql_query($query, $connection);
		return mysql_fetch_assoc($cursor);
	}
}

?>