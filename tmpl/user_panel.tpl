<h2>Панель пользователя</h2>
<p>Привет, <b>%username%</b>!<br />
<input type="button" onclick="doSomething(event)" value="Выход"/>

<script>
element.onclick = doSomething
function doSomething(event) {
    event = event || window.event
	document.location.href = 'functions.php?logout=1';
}
 </script>

