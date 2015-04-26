<?php 
require_once "modules_class.php";

class SearchContent extends Modules
{
	private $words;
	//private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
		$this->words = $this->data["words"];
	}
	
	protected function getTitle()
	{
		return "Результаты поиска: ".$this->words;
	}
	
	protected function getDescription()
	{
		return $this->words;
	}
	
	protected function getKeyWords()
	{
		return mb_strtolower($this->words);
	}
	
	protected function getTop()
	{
		//return $this->getTemplate("main_article");
	}
	
	protected function getMiddle()
	{
		//$results = $this->people->searchPeople($this->words);
		$text = "";
		//$results = $this->user->getAllUsers();
		$results = $this->user->searchPeople($this->words);
		
		if ($results === false) return $this->getTemplate("search_notfound");
		foreach ($results as $value)
		{
			$userimg["imagelink"] = $this->config->dir_usersimg.$this->user_image->getUserProfileImage($value["id"]);
			$userimg["alt"] = $value["login"];
			
			$sr["profile_img"] = $this->getReplaceTemplate($userimg, "user_profileimage");
			//echo $sr["profile_img"];
			
			$sr["name"] = $value["name"];
			$sr["userlink"] = "?user=".$value["login"];
			$sr["last_name"] = $value["last_name"];
			$sr["gender"] = $value["gender"];
			$sr["DOB"] = $value["DOB"];
			$sr["e_mail"] = $value["e_mail"];
			$sr["changelink"] = $this->getReplaceTemplate("", "void");
			
			$text .= $this->getReplaceTemplate($sr, "search_item_people");
		}
		$new_sr["search_items"] = $text;
		return $this->getReplaceTemplate($new_sr, "search_result");
		
	}
	
	protected function getBottom()
	{
		//return $this->getPagination(count($this->articles), $this->config->count_blog, $this->config->address);
	}
}
?>