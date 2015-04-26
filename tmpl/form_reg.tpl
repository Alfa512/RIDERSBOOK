<h1>Регистрация</h1>
%message%
<div id="reg" action="#" method="post">
	<form name="reg" action="functions.php" method="post">
		<table>
		<tr>
			<td>Логин:</td>
			<td>
				<input type="text" name="login" value="%login%" />
			</td>
		</tr>
		<tr>
			<td>Пароль:</td>
			<td>
				<input type="password" name="password" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<img src="/lib/captcha.php" alt="Каптча" />
			</td>
		</tr>
		<tr>
			<td>Проверочный код:</td>
			<td>
				<input type="text" name="captcha" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" name="reg" value="Регистрация" />
			</td>
		</tr>
	</table>
	</form>
</div>