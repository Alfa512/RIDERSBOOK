<form method="post" action="functions.php">
	<table>
		<tr>
			<td>Имя:</td>
			<td>
				<input type="text" name="name" value="%name%"/>
			</td>
		</tr>
		<tr>
			<td>Фамилия:</td>
			<td>
				<input type="text" name="last_name"  value="%last_name%"/>
			</td>
		</tr>
		<tr>
			<td>Пол:</td>
			<td>
				<input type="text" name="gender"  value="%gender%"/>
			</td>
		</tr>
		<tr>
			<td>Дата рождения:</td>
			<td>
				<input type="text" name="DOB"  value="%DOB%"/>
			</td>
		</tr>
		<tr>
			<td>E-Mail:</td>
			<td>
				<input type="text" name="e_mail"  value="%e_mail%"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" name="changeok" value="Готово"/>
			
				<input type="submit" name="changecancel" value="Отмена" />
			</td>
		</tr>
	</table>
	</form>
	<form enctype="multipart/form-data" method="post" action="functions.php">
		Изображение: 
		<input type="file" name="image" />
		<input type="submit" name="loadprofilephoto" value="Загрузить" />
</form>