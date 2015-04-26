<?php 
require_once "lib/config_class.php";
require_once "checkvalid_class.php";

class DataBase {
	
	private $config;
	private $mysqli;
	private $valid;
	public function __construct() 
	{
		$this->config = new Config();
		$this->valid = new CheckValid();
		$this->mysqli = new mysqli($this->config->host, $this->config->user, $this->config->password, $this->config->db);
		$this->mysqli->query("SET NAMES 'utf8'");
	}
	
	private function query($query)
	{
		return $this->mysqli->query($query);
	}
	
	private function select($table_name, $fields, $where = "", $order = "", $up = true, $limit = "")
	{
		for ($i = 0; $i < count($fields); $i++)
		{
			if ((strpos($fields[$i], "(") === false) && ($fields[$i] != "*")) $fields[$i] = "`".$fields[$i]."`";
		}
		$fields = implode(",", $fields);
		$table_name = $this->config->db_prefix.$table_name;
		//if (!$order) $order = "ORDER BY `id`";
		if (!$order) $order = "";
		else
		{
			if ($order != "RAND()")
			{
				$order = "ORDER BY `$order`";
				if (!$up) $order .= "DESC";
			}
			else $order = "ORDER BY $order";
		}
		if ($limit) $limit = "LIMIT $limit";
		if ($where) $query = "SELECT $fields FROM $table_name WHERE $where $order $limit";
		else $query = "SELECT $fields FROM $table_name $order $limit";
		//var_dump($query);
		//echo "<br />";
		$result_set = $this->query($query);
		if (!$result_set) return false;
		$i = 0;
		$data = [];
		while ($row = $result_set->fetch_assoc())
		{
			$data[$i] = $row;
			$i++;
		}
		$result_set->close();
		/*var_dump($data);
		echo "<br />";*/
		return $data;
	}
	
	public function insert($table_name, $new_values)
	{
		$table_name = $this->config->db_prefix.$table_name;
		$query = "INSERT INTO $table_name(";
		foreach ($new_values as $field => $value) $query .= "`".$field."`,";
		$query = substr($query, 0, -1);
		$query .= ") VALUES (";
		foreach ($new_values as $value) $query .= "'".addslashes($value)."',";
		$query = substr($query, 0, -1);
		$query .= ")";
		return $this->query($query);
	}
	
	private function update($table_name, $upd_fields, $where)
	{
		$table_name = $this->config->db_prefix.$table_name;
		$query = "UPDATE $table_name SET ";
		//var_dump($upd_fields);
		//foreach ($upd_fields as $field => $value) $query .= "`$field` = '".addcslashes($value)."',";
		foreach ($upd_fields as $field => $value) $query .= "`$field` = '".$value."',";
		$query = substr($query, 0, -1);
		if ($where)
		{
			$query .= " WHERE $where";
			//var_dump($query);
			//echo $query;
			return $this->query($query);
		}
		else return false;
	}
	
	public function updateOnID($table_name, $id, $upd_fields)
	{
		$this->update($table_name, $upd_fields, "id = '$id'");
		//echo "id = '$id'";
		//var_dump($upd_fields);
		//echo "<br />";
		return true;
	}
	
	public function delete($table_name, $where = "")
	{
		$table_name = $this->config->db_prefix.$table_name;
		if ($where)
		{
			$query = "DELETE FROM $table_name WHERE $where";
			return $this->query($query);
		}
		else return false;
	}
	
	public function deleteAll($table_name)
	{
		$table_name = $this->config->db_prefix.$table_name;
		$query = "TRUNCATE TABLE `$table_name`";
		return $this->query($query);
	}
	
	public function getField($table_name, $field_out, $field_in, $value_in)
	{
		$data = $this->select($table_name, array($field_out), "`$field_in`='".$value_in."'");
		if (count($data) != 1) return false;
		return $data[0][$field_out];
	}
	
	public function getFields($table_name, $field_out, $field_in, $value_in)
	{
		$data = $this->select($table_name, array($field_out), "`$field_in`='".$value_in."'");
		return $data;
	}
	
	public function getFieldOnID($table_name, $id, $field_out)
	{
		if (!$this->existsID($table_name, $id)) return false;
		return $this->getField($table_name, $field_out, "id", $id);
	}
	
	public function getAll($table_name, $order, $up)
	{
		return $this->select($table_name, array("*"), "", $order, $up);
	}
	
	public function getAllOnField($table_name, $field, $value, $order, $up) //ѕолучение всех записей таблицы по полю
	{
		//return $this->select($table_name, array("*"), "`$field`='".addcslashes($value)."'", $order, $up);
		return $this->select($table_name, array("*"), "`$field`='".$value."'", $order, $up);
	}
	
	public function getLastID($table_name)
	{
		$data = $this->select($table_name, array("MAX(`id`)"));
		return $data[0]["MAX(`id`)"];
	}
	
	public function getLastNumField($table_name, $field)
	{
		$data = $this->select($table_name, array("MAX(`$field`)"));
		return $data[0]["MAX(`$field`)"];
	}
	
	public function deleteOnID($table_name, $id)
	{
		if (!$this->existsID($table_name, $id)) return false;
		return $this->delete($table_name, "`id` = '$id'");
	}
	
	public function setField($table_name, $field, $value, $field_in, $value_in)
	{
		if (!$this->existsID($table_name, $id)) return false;
		//return $this->update($table_name, array($field => $value), "`$field_in` = '".addcslashes($value_in)."'");
		return $this->update($table_name, array($field => $value), "`$field_in` = '".$value_in."'");
	}
	
	public function setFieldOnID($table_name, $field, $value)
	{
		if (!$this->existsID($table_name, $id)) return false;
		return $this->setField($table_name, $field, $value, "id", $id);
	}
	
	public function getElementOnID($table_name, $id)
	{
		if (!$this->existsID($table_name, $id)) return false;
		$arr = $this->select($table_name, array("*"), "`id` = '$id'");
		return $arr[0];
	}
	
	public function getRandomElements($table_name, $count)
	{
		return $this->select($table_name, array("*"), "", "RAND()", true, $count);
	}
	
	public function getCount($table_name)
	{
		$data = $this->select($table_name, array("COUNT(`id`)"));
		return $data[0]["COUNT(`id`)"];
	}
	
	public function isExists($table_name, $field, $value)
	{
		//$data = $this->select($table_name, array("id"), "`$field` = '".addcslashes($value)."'");
		$data = $this->select($table_name, array("login"), "`$field` = '".$value."'");
		if (count($data) === 0) return false;
		return true;
	}
	
	private function existsID($table_name, $id)
	{
		if (!$this->valid->validID($id)) return false;
		//$data = $this->select($table_name, array("id"), "`id`='".addcslashes($id)."'");
		$data = $this->select($table_name, array("id"), "`id`='".$id."'");
		if (count($data) === 0) return false;
		return true;
	}
	
	public function search($table_name, $words, $fields)
	{
		$words = mb_strtolower($words);
		$words = trim($words);
		$words = quotemeta($words);
		if ($words == "") return false;
		$where = "";
		$arrayworsd = explode(" ", $words);
		$logic = "OR";
		
		foreach ($arrayworsd as $key => $value)
		{
			if (isset($arrayworsd[$key - 1])) $where .= $logic;
			for ($i = 0; $i < count($fields); $i++)
			{
				$where .= "`".$fields[$i]."` LIKE '%".$value."%'";
				if (($i + 1) != count($fields)) $where .= " OR";
			}
		}
		$results = $this->select($table_name, array("*"), $where);
		//print_r($results);
		return $results;
		
	}
	
	public function updateUserImage($table_name, $id_user, $upd_fields, $where)
	{
		$add_where = "";
		foreach ($where as $key => $value)
		{
			$add_where .= "$key = '$value'";
		}
		$this->update($table_name, $upd_fields, "id_user = '$id_user' AND ".$add_where);
		//echo "id_user = '$id_user' AND ".$add_where;
		//var_dump($upd_fields);
		//echo "<br />";
		return true;
	}
	
	public function __destruct()
	{
		if ($this->mysqli) $this->mysqli->close();
	}
}

?>