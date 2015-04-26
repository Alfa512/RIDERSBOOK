<?php 
require_once "global_class.php";
require_once "image_class.php";

class UserImage extends GlobalClass {
	
	public $imagecl;
	
	public function __construct($db)
	{
		$this->imagecl = new Image($db);
		parent::__construct("userphoto", $db);
	}
	
	public function addUserImage($id, $image, $is_profile)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		$imId = $this->imagecl->addImage($image);
		return $this->add(array("id_user" => $id, "id_photo" => $imId, "is_profile" => $is_profile));
	}
	
	public function editUserImage($imageId, $image)
	{
		return $this->imagecl->editImage($imageId, $image);
	}
	
	public function getUserIdByImageId($imageId)
	{
		return $this->getField("id_user", "id_photo", $imageId);
		//return $this->get($imageId);
	}
	
	public function getUserProfileImage($id)
	{
		
		$userImage["id_photo"] = "id_photo";
		$userImage["is_profile"] = "is_profile";
		$userImages = $this->getAllOnField("id_user", $id);
		//var_dump($userImages);
		//echo "<br />";
		foreach ($userImages as $key => $value)
		{
			/*var_dump($key);
			echo "<br />";
			var_dump($value);
			echo "<br />";*/
			if ($value["is_profile"] == 1)
			{
				$userImage = $this->imagecl->getImageByID($value["id_photo"]);
				break;
			}
		}
		return $userImage["content"];
		//return $this->get($imageId);
	}
	
	public function resetUserProfileImage($id)
	{
		
		$userImage["id_photo"] = "id_photo";
		$userImage["is_profile"] = "is_profile";
		$userImages = $this->getAllOnField("id_user", $id);
		//var_dump($userImages);
		//echo "<br />";
		foreach ($userImages as $key => $value)
		{
			/*var_dump($key);
			echo "<br />";
			var_dump($value);
			echo "<br />";*/
			if ($value["is_profile"] == 1)
			{
				$userImage = $this->imagecl->getImageByID($value["id_photo"]);
				break;
			}
		}
		return $userImage["content"];
	}
	
	public function getLastUserImage($id)
	{
		$userImages = $this->getAllOnField("id_user", $id);
		$userImage[] = 0;
		$i = 0;
		foreach ($userImages as $key => $value)
		{
			/*var_dump($key);
			echo "<br />";
			var_dump($value);
			echo "<br />";*/
			$userImage[$i] = $value["id_photo"];
			$i++;
		}
		return max($userImage);
	}
	
	public function __editUserImage($id_user, $upd_fields, $where)
	{
		return $this->_editUserImage($id_user, $upd_fields, $where);
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
	
}

?>