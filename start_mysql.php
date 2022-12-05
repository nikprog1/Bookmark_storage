<?php

$db;

function StartDB()
{
	global $db;
	$db = mysqli_connect("localhost","t92911m7_bkm","To7%Wvv&","t92911m7_bkm");


	if (mysqli_connect_errno()) 
	{
		print "Не удалось подключиться: %s\n".mysqli_connect_error();
		exit();
	}
	mysqli_set_charset($db, "utf8");
}

function EndDB()
{
	global $db;
	mysqli_close($db);
}	
