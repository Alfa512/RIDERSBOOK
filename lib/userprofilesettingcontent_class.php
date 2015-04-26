<?php 
require_once "modules_class.php";

class ProfileSettingContent extends Modules
{
	private $currentUser;
	//private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
		if (!$this->user_info) header("Location: /?view=reg");
		//$this->currentUser = $this->getUser();
		//$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
	}
	
	protected function getTitle()
	{
		return "Моя страница";
	}
	
	protected function getDescription()
	{
		return "Ridersbook. Настройки профиля";
	}
	
	protected function getKeyWords()
	{
		return "ridersbook, profile, ".$this->user_info["login"];
	}
	
	protected function getTop()
	{
		$temp = ["title_str" => "Настройки профиля"];
		return $this->getReplaceTemplate($temp, "user_profiletitle");;
		//return $this->getTemplate("main_article");
	}
	
	protected function getMiddle()
	{
		return $this->getReplaceTemplate($this->user_info, "user_profilesetting");
		//echo $this->articles;
		//echo $this->page;
		//return $this->getBlogArticles($this->articles, $this->page);
	}
	
	protected function getBottom()
	{
		//return $this->getPagination(count($this->articles), $this->config->count_blog, $this->config->address);
	}
}
?>