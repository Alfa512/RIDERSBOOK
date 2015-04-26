<?php 
require_once "global_class.php";

class User extends GlobalClass {
	public function __construct($db)
	{
		parent::__construct("users", $db);
	}
	
	public function addUser($login, $password, $DOB, $email)
	{
		if (!$this->checkValid($login, $password, $DOB)) return false;
		return $this->add(array("login" => $login, "password" => $password, "DOB" => $DOB, "e_mail" => $email));
	}
	
	public function editUser($id, $login, $password, $DOB)
	{
		if (!$this->checkValid($login, $password, $DOB)) return false;
		return $this->edit($id, array("login" => $login, "password" => $password, "DOB" => $DOB));
	}
	
	public function editUserProfile($id, $data)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		return $this->edit($id, $data);
	}
	
	public function isExistsUser($login)
	{
		//echo "<br />".$login."<br />";
		return $this->isExists("login", $login);
	}
	
	public function checkUser($login, $password)
	{
		$user = $this->getUserOnLogin($login);
		if (!$user) return false;
		return $user["password"] === $password;
	}
	
	public function getUserOnLogin($login)
	{
		$id = $this->getField("id", "login", $login);
		return $this->get($id);
	}
	
	public function getAllUsers()
	{
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		return $this->getAll();
	}
	
	public function searchPeople($words)
	{
		return $this->search($words, array("name", "last_name", "login"));
	}
	
	private function checkValid($login, $password, $DOB)
	{
		if (!$this->valid->validLogin($login)) return false;
		if (!$this->valid->validHash($password)) return false;
		if (!$this->valid->validTimeStamp($DOB)) return false;
		return true;
	}
	
	/*
	<a href="%address%functions.php?logout=1">Выход</a>
	
	<script>
  var button = document.querySelector("button");
  button.addEventListener("mousedown", function(event) {
    if (event.which == 1)
      console.log("Левая");
    else if (event.which == 2)
      console.log("Средняя");
    else if (event.which == 3)
      console.log("Правая");
  });
</script>
	*/
}

?>