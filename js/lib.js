//lib.js
let id;
let dol;
export function InitButton()
{ 
	$("#button1").click(Button1);
}

function Button1()
{
	dol=document.querySelector('#AJAX').value; //получить данные из формы
	document.querySelector(".flexshot").remove();
	$("#newdata").load('AJAX_test.php?dol='+dol);//вызвать поиск из БД
	//console.log("Клик");	
	//console.log(dol);	
}
//вешаем событие на ввод в форму
export function Input()
{
	console.log("Обработчик");
	const input=document.querySelector('#AJAX');
	input.addEventListener('input', Button1);
}
