<?php session_start(); require_once "main.php"; require_once "start_mysql.php";
$url = htmlspecialchars($_POST['siteurl']);
// Удаляем протокол из адреса
$url = str_replace (['http://', 'https://'], '', $url); 
// Удаляем пробелы и слеш
$url = trim($url, ' /'); 
StartDB();
// Получение заголовка сайта
$tab = SiteTitle($url);
// Получение скриншота сайта
$shot = SiteScreenshot($url);
// Код группы Общие
$groupname = $_POST['groupname'];
// Получение кода клиента
$client = $_SESSION['iduser'];

// проверка наличия названия группы и вставка названия из запроса
$SQL= "SELECT `Код группы` FROM `Группы` WHERE `Группа` LIKE '$groupname'";
if ($result = mysqli_query($db, $SQL))
	{	
		if (mysqli_num_rows($result) == 0) // наличие совпадений
		{
			$SQL = "INSERT INTO Группы (`Группа`, `Код клиента`) VALUES ('$groupname', '$client')";	
			mysqli_query($db, $SQL);
		}
	}
	else 
		{
			printf("Ошибка в запросе: %s\n", mysqli_error($db));
		}	
// вставка в `Закладки` 
$SQL = "INSERT INTO Закладки (`Закладка`, `Адрес`, `Скриншот`, `Код группы`, `Код клиента`) VALUES 	('$tab', '$url', '$shot', (SELECT `Код группы` FROM `Группы` WHERE `Группа` LIKE '$groupname'), '$client')";
mysqli_query($db, $SQL);
EndDB();
header("Location: index.php");
