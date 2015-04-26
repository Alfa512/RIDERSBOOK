<?php 
require_once "global_class.php";

class Posts extends GlobalClass {
	public function __construct($db)
	{
		parent::__construct("posts", $db);
	}
	
	public function addPost($id_user, $postText, $time = null, $access = 0)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		$this->add(array("id_user" => $id_user, "text" => $postText, "dateTime" => $time, "access" => $access));
		return $this->getLastID();
	}
	
	public function editPost($id, $postText)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		return $this->edit($id, array("id_user" => $id_user, "text" => $postText, "dateTime" => $time, "access" => $access));
	}
	
	public function getPostByID($id) //Получение списка постов данного пользователя
	{
		return $this->get($id);
	}
	
	public function getAllPosts()
	{
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		return $this->getAll();
	}
	
	public function getPostsByUserID($id_user) //Получение конкретной записи
	{
		//echo $id_user;
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		return $this->getAllOnField("id_user", $id_user);
	}
	
	public function getLastUserPost($id_user)
	{
		$last_post = $this->getAllOnField("id_user", $id_user);
		//var_dump($last_post);
		$posts_id[] = 0;
		$i = 0;
		foreach($last_post as $key => $value)
		{
			//echo $value["id"]."<br />";
			$posts_id[$i] = (int)$value["id"];
			$i++;
		}
		//var_dump($posts_id);
		//echo "<br />";
		//echo max($posts_id);
		return max($posts_id);
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