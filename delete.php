<?php require_once "start_mysql.php";
StartDB();
	$id = $_GET['id'];

	// Удаление файла с картинкой сайта
	$SQL = "SELECT * FROM Закладки WHERE `Код закладки`=".$id;

	if ($result_item = mysqli_query($db, $SQL)) 
	{
		$row = mysqli_fetch_assoc($result_item);
		$shot = $row['Скриншот'];
		unlink($shot);
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}

	// Удаление записи из базы данных
	$SQL = "DELETE FROM Закладки WHERE `Код закладки`='$id'";

	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
EndDB();
header("Location: ".$_SERVER['HTTP_REFERER']);
