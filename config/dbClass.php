<?php
global $dbConn;
class my_mysqli extends mysqli {
    public function __construct($dbHost, $dbUser, $dbPass, $dbName) {
        parent::__construct($dbHost, $dbUser, $dbPass, $dbName);

        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
    }
}
function establishcon()
{
	
	$dbConn = new my_mysqli('localhost', 'staffexp_staff_staging', 'VoF,p^Qc{m0@', 'staffexp_staffex_staging');
	return $dbConn;
}

function closeconn($dbConn)
{
  mysqli_close($dbConn);
}
function dbQuery($linko,$sql)
{
    $result = mysqli_query($linko,$sql) or die(mysqli_error($linko).'<p><b>SQL:</b><br>'.$sql.'</p>');
    return $result;
}

function dbFetchObject($result)
{
    return mysqli_fetch_object($result);
}

function dbNumRows($result)
{
    return mysqli_num_rows($result);
}
function dbFetchArray($result) {
    return mysqli_fetch_array($result);
}
function dbFetchAssoc($result)
{
	return mysqli_fetch_assoc($result);
}
function dbInsertId($linkto)
{
    return mysqli_insert_id($linkto);
}
?> 