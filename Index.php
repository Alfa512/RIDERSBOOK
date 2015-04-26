<?php 
	mb_internal_encoding("UTF-8");
	require_once "lib/database_class.php";
	require_once "lib/frontpagecontent_class.php";
	require_once "lib/regcontent_class.php";
	require_once "lib/messagecontent_class.php";
	require_once "lib/searchcontent_class.php";
	require_once "lib/userprofilecontent_class.php";
	require_once "lib/userprofilesettingcontent_class.php";
	require_once "lib/loadphotocontent_class.php";
	require_once "lib/friendspagecontent_class.php";
	require_once "lib/allpeople_content_class.php";
	
	session_start();
	$db = new DataBase();
	$view = $_GET["view"];
	$key = key($_GET);
	$content = "";
	if ($view == "" or $view)
	{
		switch ($view)
		{
			case "":
				$content = new FrontPageContent($db);
				break;
			case "reg":
				$content = new RegContent($db);
				break;
			case "message":
				$content = new MessageContent($db);
				break;
			case "search":
				$content = new SearchContent($db);
				break;
			case "people":
				$content = new AllPeoplePageContent($db);
				break;
			case "profile":
				$content = new ProfilePageContent($db, $_SESSION["login"]);
				//echo $content->getContent();				
				break;
			case "profilesetting":
				$content = new ProfileSettingContent($db);
				break;
			case "loadphoto":
				$content = new LoadPhoto($db);
				break;
			case "loadphoto":
				$content = new LoadPhoto($db);
				break;
			case "friends":
				//$content = new FriendsPageContent($db, $_GET["user"]);
				$content = new FriendsPageContent($db);
				break;
			default: exit;
		}
	}
	if ($key)
	{
		switch ($key)
		{
			case "user":
				//echo $_GET["view"];
				$content = new ProfilePageContent($db, $_GET["user"]);
				break;
			default: $content->getContent();
		}
	}
	
	echo $content->getContent();
?>
