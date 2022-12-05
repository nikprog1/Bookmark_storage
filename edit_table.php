<?php session_start(); $title = "Правка закладок"; require_once "header.php"; ?>

<div id="wrapper">
<div id="header">
	<h2>Правка таблицы закладок</h2>
</div> 

	<div id="content">
		
	<?php	
	
		if(isset($_SESSION['iduser']))
		{
			StartDB();
			EditDB();
			EndDB();
		}
		else
		{
			print "У вас нет прав просмотра этой страницы";
		}	
	?>
	</div>
	<div id="footer">
		<br> <br>
		<a href="index.php">Вернуться на главную</a>	
	</div>
</div>

<?php require_once "footer.php"; ?>
