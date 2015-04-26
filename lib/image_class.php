<?php 
require_once "global_class.php";

class Image extends GlobalClass {
	public function __construct($db)
	{
		parent::__construct("images", $db);
	}
	
	public function addImage($image)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		$this->add(array("content" => $image));
		return $this->getLastID();
	}
	
	public function editImage($id, $image)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		return $this->edit($id, array("content" => $image));
	}
	
	/*public function checkUser($login, $password)
	{
		$user = $this->getUserOnLogin($login);
		if (!$user) return false;
		return $user["password"] === $password;
	}*/
	
	public function getImageByID($id)
	{
		return $this->get($id);
	}
	
	public function getAllImages()
	{
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		return $this->getAll();
	}
	
	/*private function checkValid($login, $password, $DOB)
	{
		if (!$this->valid->validLogin($login)) return false;
		if (!$this->valid->validHash($password)) return false;
		if (!$this->valid->validTimeStamp($DOB)) return false;
		return true;
	}*/
}

?>