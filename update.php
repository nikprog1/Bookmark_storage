<?php session_start(); require_once "start_mysql.php";
StartDB();
$id = $_POST['id'];
$tab  = htmlspecialchars($_POST['tab']);
$siteurl  = htmlspecialchars($_POST['siteurl']);
$group = $_POST['group'];
$newgroup= htmlspecialchars($_POST['newgroup']);
$iduser= $_SESSION['iduser'];
//изменение группы закладки
if ($newgroup == NULL)
{		
$SQL = "UPDATE Закладки SET `Закладка`='$tab', `Адрес`='$siteurl', `Код группы`='$group' WHERE `Код закладки`='$id'";//меняем группу закладки
if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
}	
else 
{
//получение ID запись новой группы закладки в БД (группы нет  в выпадающем списке)
$SQL = "SELECT `Код группы` FROM `Группы` WHERE `Группа`='1000'";
if ($result = mysqli_query($db, $SQL)) 
	{
	$row = mysqli_fetch_assoc($result);
	if ($row==NULL)
		{
		$SQL = "INSERT INTO `Группы` (`Группа`,`Код клиента`) VALUES ('$newgroup','$iduser')";//запись новой группы закладки в БД 
		if (!$result = mysqli_query($db, $SQL)) 
			{
			printf("Ошибка в запросе: %s\n", mysqli_error($db));
			}	
		}	
	}
$SQL = "UPDATE Закладки SET `Закладка`='$tab', `Адрес`='$siteurl', `Код группы`=(SELECT `Код группы` FROM `Группы` WHERE `Группа`='$newgroup') WHERE `Код закладки`='$id'";	
if (!$result = mysqli_query($db, $SQL)) 
	{		
		printf("Ошибка в запросе: %s\n", mysqli_error($db));	
	}	
			
}	
EndDB();
header("Location: edit_table.php");	
