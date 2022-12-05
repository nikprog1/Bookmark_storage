<?php
$APIkey = 'e0a638'; // API key с сайта https://www.screenshotmachine.com/
function InitDB()
{
	global $db;
	// Создание таблицы Закладки
	if (mysqli_query($db, "DROP TABLE IF EXISTS Закладки;") === TRUE)
	{
		print "Таблица Закладки удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	if (mysqli_query($db, "DROP TABLE IF EXISTS Группы;")  === TRUE)
	{
		print "Таблица Группы удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	
	$SQL = "CREATE TABLE Закладки ( 
	`Код закладки` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`Закладка` VARCHAR(255) NOT NULL, 
	`Адрес` VARCHAR(2048) NOT NULL, 
	`Скриншот` VARCHAR(255) DEFAULT NULL,
	`Код клиента` INT NOT NULL,
	`Код группы` INT NOT NULL
	);";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица Закладки создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	// Создание таблицы Группы
	$SQL = "CREATE TABLE Группы ( 
	`Код группы` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`Группа` VARCHAR(50) NOT NULL,
	`Код клиента` INT NOT NULL)
	";
	
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица Группы создана<br>";
	}
	else
	{
		printf("Ошибка создания таблицы 'Группы': %s\n", mysqli_error($db));
	}
	
	// Добавление группы Общая
		$SQL = "INSERT INTO Группы (`Группа`, `Код клиента` ) VALUES ('Общая', '1')";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Запись 'Общая' в таблицу Группы добавлена.<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	
	// Создание таблицы Клиенты
	if (mysqli_query($db, "DROP TABLE IF EXISTS Клиенты;") === TRUE)
	{
		print "Таблица Клиенты удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	$SQL = "CREATE TABLE Клиенты 
	( 
	`Код клиента` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`Логин` VARCHAR(50) NOT NULL, 
	`Пароль` VARCHAR(255) NOT NULL,
	`Доступ` int NOT NULL,
	`Регистрация` TIMESTAMP NOT NULL
	);";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица Клиенты создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	// Добавляем записи администратора и первого клиента
	$hash_pass1 = password_hash('admin100', PASSWORD_DEFAULT);
	$hash_pass2 = password_hash('1', PASSWORD_DEFAULT);
	$SQL = "INSERT INTO Клиенты (`Логин`, `Пароль`, `Доступ`) 
						VALUES 	('admin', '".$hash_pass1."', '10'),
								('1', '".$hash_pass2."', '1')";
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Запись администратора в таблицу Клиенты добавлена.<br>";
	}
	else
	{
		printf("Ошибка добавления записи администратора: %s\n", mysqli_error($db));
	}

	
}
// Запрос к БД (где используется????)
function GetDB()
{
	global $db;
	$SQL = "
			SELECT Закладки.`Закладка`, Закладки.`Адрес`, Группы.`Группа`
			FROM Закладки JOIN Группы 
			ON Закладки.`Код группы` = Группы.`Код группы`";
	if ($result = mysqli_query($db, $SQL)) 
	{
		//printf ("Число строк в запросе: %d<br>", mysqli_num_rows($result));
		print "<table border=1 cellpadding=5>"; 
		// Выборка результатов запроса 
		while( $row = mysqli_fetch_assoc($result) )
		{ 
			print "<tr>"; 
			printf("<td>%s</td><td>%s</td><td>%s</td>", $row['Закладка'], $row['Адрес'], $row['Группа']); 
			print "</tr>"; 
		} 
		print "</table>"; 
		mysqli_free_result($result);
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	 
}	
// Отображение закладок
function ShowSites()
{
	global $db;
	$SQL = "SELECT * FROM Закладки JOIN `Группы` ON `Группы`.`Код группы`=`Закладки`.`Код группы` WHERE `Закладки`.`Код клиента` = '".$_SESSION['iduser']."' ORDER BY `Закладки`.`Код группы` ASC";
	//print $SQL;
	if ($result = mysqli_query($db, $SQL)) 
	{
		//printf ("Число строк в запросе: %d<br>", mysqli_num_rows($result));
		// Выборка результатов запроса 
		print "<div id='newdata'>";
		$a=$b=$c=$n=0;
		print "<div class='flexshot'>";
		while( $row = mysqli_fetch_assoc($result) )
		{ 
			$a=$row['Код группы'];
			if ($a>$b)
			{
				print "<div class='group'>".$row['Группа']."</div>";
				$b=$a;
			}
			$url = 'http://'.$row['Адрес'];
			print "<div><a href='".$url."'><img class='shot' src='".$row['Скриншот']."'></a></div>";
		} 
		print "</div>";
		print "</div>";

	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	 
}	



// Функция правки таблицы закладок 
function EditDB()
{
	global $db;
	$SQL = "SELECT * FROM Закладки WHERE `Код клиента` = ".$_SESSION['iduser'];
	if ($result = mysqli_query($db, $SQL)) 
	{
		print "<table border=1 cellpadding=5>";
		while ($row = mysqli_fetch_assoc($result)) 
		{
			print "<tr>"; 
			printf("<td>%s</td><td>%s</td>", $row['Закладка'], $row['Адрес']); 
			print "<td><a href='edit.php?id=".$row['Код закладки']."'>Открыть</a></td>";
			print "<td><a href='delete.php?id=".$row['Код закладки']."'>Удалить</a></td>";
			print "</tr>"; 			
		}	 
		print "</table><br>";
	}
}


// Сохраняет скриншот в файл и возвращает его имя
function SiteScreenshot($url)
{
	global $APIkey;
	// Удаляем протокол из адреса
	$url = str_replace (['http://', 'https://'], '', $url); 
	// Удаляем пробелы и слеш
	$url = trim($url, ' /'); 
	// К имени файла добавляем код клиента
	$file = $url.$_SESSION['iduser'];
	// Получаем хэш для имени файла
	$filename = md5($file) . ".png";
	// Папка, где хранятся скриншоты сайтов
	$dir = "pics/";
	// Если скриншот существует, то выдаем его на экран
	if (is_file($dir.$filename)) 
	{
		return $dir.$filename;
	}
	// Иначе создаем скриншот
	else 
	{
		// Запрос для скриншота
		$geturl = "https://api.screenshotmachine.com?key=" . $APIkey . "&dimension=320x240&format=png&url=" . $url;
		// Получаем скриншот
		$screenshot = file_get_contents($geturl);
		// Создаем файл
		$openfile = fopen($dir.$filename, "w+");
		// Сохраняем изображение
		$write = fwrite($openfile, $screenshot);
		return $dir.$filename;
	}
}
// Возвращает заголовок сайта
function SiteTitle($url)
{
	// Если нет протокола в адресе добавляем его
	if ((strpos($url, 'http://') === false) && (strpos($url, 'https://') === false)) 
	{
		$url = 'http://' . $url;
	}
	$fp = file_get_contents($url);
	if (!$fp) 
	{
		return null;
	}
	$res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
	$title_matches = mb_convert_encoding($title_matches, "UTF-8");


	if (!$res) 
	{
		return null;
	}
	// Чистка заголовка
	$title = preg_replace('/\s+/u', ' ', $title_matches[1]);
	$title = trim($title);
	return $title;
}

// Проверка авторизации
function CheckLogin()
{
	// Если авторизация есть
	if(isset($_SESSION['iduser']))
	{
		ShowSites();
		return;
	}
	// Проверка логина
	if(isset($_POST['userlogin']))
	{
		$_SESSION['login'] = $_POST['userlogin'];
		$_SESSION['password'] = $_POST['userpass'];
		// Проверка пароля
		if(CheckPassword())
		{
			ShowSites();
		}
		else
		{
			print "<br>Доступ запрещен";
			print "<a href='index.php'><br>Введите логин и пароль повторно</a>";
		}
    }
    // Если авторизации нет
	else
	{
		ShowLogin();
	}
}
// Проверка авторизации см.CheckLogin()
function CheckPassword() 
{
	global $db;
    // Составляем строку запроса
    $SQL = "SELECT * FROM `Клиенты` WHERE `Логин` LIKE '".$_SESSION['login']."'";

	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если нет пользователя с таким логином, то завершаем функцию
		if(mysqli_num_rows($result)==0) 
		{
			print "<br>Пара логин-пароль не совпадает";
			return FALSE;
		}
		// Если логин есть, то проверяем пароль
		$row = mysqli_fetch_assoc($result); 
		if (password_verify($_SESSION['password'], $row['Пароль']))
		{
			//print "<br>Пароль совпадает<br>";
			$_SESSION['iduser']=$row['Код клиента'];
			if ($row['Доступ'] < 10)
		{ 
			$_SESSION['admin_is_set']=FALSE;
		}
		else
		{
			$_SESSION['admin_is_set']=TRUE;
		}
			return TRUE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
    unset($_SESSION['iduser']);
    print "Пара логин-пароль не совпадает<br>";
    return FALSE;
}

//Регистрация пользователя
function RegUser() 
{
	global $db;
	// Проверка данных
	if(!$_POST['user_login']) 
	{
		print "<br>Не указан логин";
		return FALSE;
	} 
	elseif(!$_POST['user_password']) 
	{
		print "<br>Не указан пароль";
		return FALSE;
	}
	
	// Проверяем не зарегистрирован ли уже пользователь
	$SQL = "SELECT `Логин` FROM `Клиенты` WHERE `Логин` LIKE '".$_POST['user_login']. "'";

	// Делаем запрос к базе
	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если есть пользователь с таким логином, то завершаем функцию
		if(mysqli_num_rows($result) > 0) 
		{
			print "<br>Пользователь с указанным логином уже зарегистрирован.";
			return FALSE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	} 
	// Если такого пользователя нет, регистрируем его
	$hash_pass = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
	$SQL = "INSERT INTO `Клиенты` 
			(`Логин`,`Пароль`,`Доступ`) VALUES 
			('".$_POST['user_login']. "','".$hash_pass. "', '1')";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		//print "<br>Пользователь зарегистрирован";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
		return FALSE;
	}
	
	return TRUE;
}

// Форма ввода данных для входа
function ShowLogin()
{
	?>

	<form  id="center" action="index.php" method="post" >
		<p align="center">Логин<br>
		<input name="userlogin"size="20"
		type="text" value=""></p>
		<p align="center">Пароль<br>
		<input name="userpass"size="20"
		type="password" value=""></p> 
		<p align="center"><input name="login" type="submit" value="Войти" ></p>
		<p align="center">Еще не зарегистрированы?</p> 
		<p align="center"> <a href = "register.php">Регистрация</a></p>
	</form>
	<?php
}

// Форма ввода данных для добавления сайта и поиска по сайту
function ShowAddSite()
{
	if(isset($_SESSION['iduser']))
	{
		?>	
		<form action="addsite.php" method="post">
				<p>Введите адрес сайта:<input name="siteurl" maxlength=2048 size=60></p>
				<p>Введите имя группы:<input name="groupname" maxlength=2048 size=60></p>
				<input type="submit" value="Добавить сайт">
		</form>	
				<p id="result1">Поиск по имени сайта</p>
				<input type="text" id="AJAX" name ="test"><br>
				<a href="edit_table.php">Правка закладок</a>
				<a href="exit.php">Завершить работу</a>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="module" src="js/main.js"></script>		
		<?php
		if($_SESSION['admin_is_set']===TRUE)
		{
		print "<a href=./admin/index.php>Администрирование сайта</a>";
	}
	}
}	

// Начало/завершение страниц
function StartPage()
{	
?>	
	<div id="wrapper">
<div id="header">
</div> 


<div id="content">
<?php
	
}

function EndPage()
{	
?>	
</div>
<div id="footer">
</div>

</div>

<?php
	
}
