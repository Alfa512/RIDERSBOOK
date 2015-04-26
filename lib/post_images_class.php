<?php 
require_once "global_class.php";
require_once "image_class.php";

class PostImages extends GlobalClass {
	
	private $imagecl;
	
	public function __construct($db)
	{
		$this->imagecl = new Image($db);
		parent::__construct("postimages", $db);
	}
	
	public function addPostImage($id, $imId)
	{
		//if (!$this->checkValid($login, $password, $DOB)) return false;
		//$imId = $this->imagecl->addImage($image);
		return $this->add(array("id_post" => $id, "id_image" => $imId));
	}
	
	public function editPostImage($imageId, $image)
	{
		return $this->imagecl->editImage($imageId, $image);
	}
	
	public function getPostIdByImageId($imageId)
	{
		return $this->getField("id_post", "id_image", $imageId);
		//return $this->get($imageId);
	}
	
	/*public function checkUser($login, $password)
	{
		$user = $this->getUserOnLogin($login);
		if (!$user) return false;
		return $user["password"] === $password;
	}*/
	
	public function getImagesByPost($id)
	{
		
		$images = "";
		$i = 0;
		$postsImages = $this->getAllOnField("id_post", $id);
		//var_dump($postsImages);
		foreach ($postsImages as $key => $value)
		{
			$images[$i] = $this->getImageByID($value["id_image"]);
			$i++;
		}
		//var_dump($images);
		return $images;
	}
	
	public function getImageByID($id)
	{
		return $this->imagecl->getImageByID($id);
	}
	
	public function getLastUserPostImage($id_post)
	{
		$userPosts = $this->getAllOnField("id_post", $id_post);
		//var_dump($userPosts);
		if (count($userPosts) == 0) return false;
		$postImages = 0;
		$i = 0;
		foreach($userPosts as $key => $value)
		{
			$postImages[$i] = $value["id_image"];
		}
		//var_dump($postImages);
		//echo max($postImages);
		return max($postImages);
	}
	
	public function getAllPostsAndImages()
	{
		//$id = $this->getField("id", "login", $login);
		//echo $this->getAll();
		return $this->getAll();
	}
	
}

?>