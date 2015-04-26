<?php 
require_once "modules_class.php";

class ProfilePageContent extends Modules
{
	private $currentUser;
	private $sessionUser;
	//private $page;
	
	public function __construct($db, $login)
	{
		parent::__construct($db);
		if (!$this->user_info) header("Location: /?view=reg");
		$this->currentUser = $this->user->getUserOnLogin($login);
		$this->sessionUser = $this->user->getUserOnLogin($_SESSION["login"]);
		//$this->currentUser = $this->getUser();
		//$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
	}
	
	protected function getTitle()
	{
		return "Моя страница";
	}
	
	protected function getDescription()
	{
		return "Ridersbook. Мой профиль";
	}
	
	protected function getKeyWords()
	{
		return "ridersbook, profile, ".$this->currentUser["login"];
	}
	
	protected function getTop()
	{
		$temp = ["title_str" => "Моя страница"];
		$user = $this->currentUser;
		$user["userlink"] = "?user=".$this->currentUser["login"];
		if ($this->currentUser["login"] == $_SESSION["login"]) $user["changelink"] = $this->getReplaceTemplate("", "user_profile_changelink");
		else 
		{
			if ($this->friend->isMyFriend($this->sessionUser["id"], $this->currentUser["id"]))
			{
				$addfriend["message"] = $this->currentUser["login"]." у вас в друзьях";
				$user["changelink"] = $this->getReplaceTemplate($addfriend, "message_string");
			}
			else
			{
				$addfriend["login"] = $this->currentUser["login"];
				$user["changelink"] = $this->getReplaceTemplate($addfriend, "add_to_friend_form");
			}
			
		}
		$imgid["imagelink"] = $this->config->dir_usersimg.$this->user_image->getUserProfileImage($this->currentUser["id"]);
		$imgid["alt"] = "";
		//echo $this->getReplaceTemplate($imgid, "user_profileimage");
		$user["profile_img"] = $this->getReplaceTemplate($imgid, "user_profileimage");
		//var_dump($user);
		return $this->getReplaceTemplate($temp, "user_profiletitle").$this->getReplaceTemplate($user, "user_profilepage");
		//return $this->getTemplate("main_article");
	}
	
	protected function getMiddle()
	{
		$userPosts = "";
		$postImages = "";
		$allUserPosts = $this->posts->getPostsByUserID($this->currentUser["id"]);
		foreach ($allUserPosts as $key => $value)
		{
			$postImagesLink = $this->post_images->getImagesByPost($value["id"]);
			//var_dump($allUserPosts);
			//var_dump($postImagesLink);
			//var_dump($value);
			//echo "<br />";
			if ($postImagesLink != 0)
			{
				foreach ($postImagesLink as $key2 => $value2)
				{
					//var_dump($postImagesLink);
					//var_dump($value2);
					//echo $value2["content"];
					//echo "<br />";
					$replace["imagelink"] = $this->config->dir_postsimg.$value2["content"];
					$replace["alt"] = "";
					//echo $replace["imagelink"];
					$postImages .= $this->getReplaceTemplate($replace, "user_post_image");
					
				
				}
			}
			$postText["posttext"] = $value["text"];
			$postText["postimage"] = $postImages;
			//var_dump ($postText);
			
			$userPosts .= $this->getReplaceTemplate($postText, "user_post");
			//var_dump($postText);
			unset($postText);
			unset($postImagesLink);
			unset($postImages);
			//var_dump($postText);
		}
		$temp["text"] = "Что у вас нового?";
		if ($this->currentUser["login"] == $_SESSION["login"]) $userPosts .= $this->getReplaceTemplate($temp, "post_content_input");
		else $userPosts .= $this->getReplaceTemplate($temp, "void");
		return $userPosts;
	}
	
	protected function getBottom()
	{
		//return $this->getPagination(count($this->articles), $this->config->count_blog, $this->config->address);
	}
}
?>