<?php $title = "Добавление закладки"; require_once "header.php"; ?>

<div id="wrapper">
<div id="header">
	<h2>Добавление закладки</h2>
</div> 

<div id="content">
<?php

	$tab  = htmlspecialchars($_POST['tab']);
	$siteurl = htmlspecialchars($_POST['siteurl']);
	$group = $_POST['group'];
	$client = $_POST['client'];	
	print "Закладка - $tab<br>";
	print "Адрес - $siteurl<br>";	
	print "Код группы - $group<br>";	
	print "Код клиента - $client<br>";	
	
	StartDB();
	$SQL = "INSERT INTO Закладки
					(`Закладка`, `Адрес`, `Код группы`, `Код клиента`) 
			VALUES 	('$tab', '$siteurl', '$group', '$client')";		
	print $SQL."<br>";
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Запись в таблицу 'Закладки' добавлена.<br>";
	}
	else
	{
		printf("Ошибка добавления записи: %s\n", mysqli_error($db));
	}
	print '<a href="edit_table.php">Вернуться к таблице</a>';
	
	EndDB();
?>
	
</div>
<div id="footer">
</div>

</div>

<?php require_once "footer.php"; ?>
