<?php session_start(); $title = "Хранение закладок"; require_once "header.php"; ?>	
		<div id="wrapper">

			<div id="header">
				<h2>Сервис визуальных закладок</h2>
			</div> 
			
			<div id="content_shots">
			<?php	
				StartDB();
			//InitDB(); // Первоначальное создание таблиц
				CheckLogin();
				EndDB(); 
			?>
			</div>
			<div id="footer">
				<br>
				<?php ShowAddSite(); ?>
			</div>
		</div> 	
	</body>
</html>
