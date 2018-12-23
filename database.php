<?php

/**
 * $config file looks like this:
 * Array (
 *   'database' => Array(
 *     'host' => 'localhost'
 *     'db' => 'journalclub'
 *     'username' => 'UserName'
 *     'password' => 'UserPassword'
 *   )
 * )
 */

function connect_to_db()
{
$config = parse_ini_file('conf/JournalClub.conf', true);

 /* get the credentials */
$host     = $config['database']['host'];
$db       = $config['database']['db'];
$username = $config['database']['username'];
$password = $config['database']['password'];
$link = mysqli_connect("$host", "$username", "$password") or die("Cannot connect to the database");  
mysqli_select_db($link,"$db") or die("Cannot select the database");  

return $link;
}

connect_to_db();
?>
