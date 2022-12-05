<?php session_start(); require_once "main.php"; require_once "start_mysql.php"; ?>
<?php
	StartDB();
	global $db;
	$SQL1 = "SELECT * FROM Закладки WHERE `Код клиента` ='".$_SESSION['iduser']."'AND `Адрес` LIKE '%".$_GET['dol']."%' ORDER BY `Код группы` ASC";
	$SQL = "SELECT * FROM Закладки JOIN `Группы` ON `Группы`.`Код группы`=`Закладки`.`Код группы` WHERE `Закладки`.`Код клиента` = '".$_SESSION['iduser']."'AND `Адрес` LIKE '%".$_GET['dol']."%'  ORDER BY `Закладки`.`Код группы` ASC";
	//print $SQL;
	if ($result = mysqli_query($db, $SQL)) 
	{
		$a=$b=$c=$n=0;
		print "<div class='flexshot'>";
		while( $row = mysqli_fetch_assoc($result) )
		{ 
			$a=$row['Код группы'];
			//mysqli_data_seek($result, $n++);
			//$pointer= mysqli_fetch_row($result);
			//if ($pointer[5]<)
			if ($a>$b)
			{
				print "<div class='group'>".$row['Группа']."</div>";
				$b=$a;
			}
			$url = 'http://'.$row['Адрес'];
			print "<div class='shot'><a href='".$url."'><img src='".$row['Скриншот']."'></a></div>";
		} 
		print "</div>";
		mysqli_free_result($result);
		
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	 EndDB();
?>	
