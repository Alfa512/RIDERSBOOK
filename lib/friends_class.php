<?php 
require_once "global_class.php";
require_once "user_class.php";

class UserFriends extends GlobalClass {
	
	private $user;
	
	public function __construct($db)
	{
		$this->user = new User($db);
		parent::__construct("userfriends", $db);
	}
	
	public function addFriend($id_user, $id_friend, $confirm)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		$this->add(array("id_user" => $id_user, "id_friend" => $id_friend, "confirm" => $confirm));
		return $this->getLastID();
	}
	
	/*public function editPost($id, $postText)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		return $this->edit($id, array("id_user" => $id_user, "text" => $postText, "dateTime" => $time, "access" => $access));
	}*/
	
	/*public function getPostByID($id) //Получение списка постов данного пользователя
	{
		return $this->get($id);
	}*/
	
	public function getAllUserFriends($id_user)
	{
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		/*$as = $this->getAllOnField("id_user", $id_user);
		var_dump($as);*/
		return $this->getAllOnField("id_user", $id_user);
	}
	
	public function getAllFriendUsers($id_friend)
	{
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		/*$as = $this->getAllOnField("id_friend", $id_friend);
		var_dump($as);*/
		return $this->getAllOnField("id_friend", $id_friend);
	}
	
	public function getAllMyFriends($id_user)
	{
		$a = $this->getAllUserFriends($id_user);
		$b = $this->getAllFriendUsers($id_user);
		$c = $a + $b;
		$friends[] = "";
		//var_dump($user);
		$i = 0;
		foreach ($c as $key => $value)
		{
			//echo $value["id_friend"];
			$friends[$i] = $this->user->get($value["id_friend"]);
			$i++;
		}
		//var_dump($friends);
		return $friends;
	}
	
	public function isMyFriend($id_my, $id_friend)
	{
		$myFriends = $this->getAllUserFriends($id_my);
		$friendsMy = $this->getAllFriendUsers($id_friend);
		if ($myFriends)
		{
			foreach($myFriends as $key => $value)
			{
				if ((int)$id_friend == (int)$value["id_friend"]) return true;
			}
		}
		elseif ($friendsMy)
		{
			foreach($friendsMy as $key => $value)
			{
				if ((int)$id_friend == (int)$value["id_user"]) return true;
			}
		}
		return false;
	}
	
	/*public function getPostsByUserID($id_user) //Получение конкретной записи
	{
		//echo $id_user;
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		return $this->getAllOnField("id_user", $id_user);
	}*/
	
	/*public function getLastUserPost($id_user)
	{
		$last_post = $this->getAllOnField("id_user", $id_user);
		//var_dump($last_post);
		$posts_id[] = 0;
		$i = 0;
		foreach($last_post as $key => $value)
		{
			echo $value["id"]."<br />";
			$posts_id[$i] = (int)$value["id"];
			$i++;
		}
		//var_dump($posts_id);
		//echo "<br />";
		//echo max($posts_id);
		return max($posts_id);
	}*/
	
	/*private function checkValid($login, $password, $DOB)
	{
		if (!$this->valid->validLogin($login)) return false;
		if (!$this->valid->validHash($password)) return false;
		if (!$this->valid->validTimeStamp($DOB)) return false;
		return true;
	}*/
}

?>