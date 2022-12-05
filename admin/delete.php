<?php $title = "Удаление пользователя"; require_once "header.php"; 
	
	StartDB();
	$id = $_GET['id'];
	$SQL = "DELETE FROM Клиенты WHERE `Код клиента`='$id'";
//	print $SQL;
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	EndDB();
	header("Location: ".$_SERVER['HTTP_REFERER']);
