<?php
	
	include 'bbcodes.php';

	class Lists
	{
	
		public static function add($t, $ml)
		{
			$user_id = $_SESSION['user_id'];

			$stmt = $ml->prepare("INSERT INTO `lists` SET `name`=?, `owner_id`=?");

			$stmt->bind_param('si', $t, $user_id);
			$result = $stmt->execute();

			if($result != true)
			{
	            throw new Exception("Couldn't update item!");
	    	}else
	        {
	        	echo $ml->insert_id;
	        }
		}

		public static function rename($id, $name, $ml)
		{

			if(!$name) 
			{
				throw new Exception("Error Processing Request", 1);
			}

			$stmt = $ml->prepare("UPDATE `lists`
                        SET name=?
                        WHERE id=?");
			$stmt->bind_param('si', $name, $id);
			$result = $stmt->execute();

			if($result != true)
            	throw new Exception("Couldn't update item!");
		}

		public static function delete($id, $mysqli){

			$stmt = $mysqli->prepare("DELETE FROM `lists` WHERE id=?");
			$stmt->bind_param('i', $id);
			$result = $stmt->execute();


        	if($result != true)
            	throw new Exception("Couldn't delete list!");

            $stmt = $mysqli->prepare("DELETE FROM `todos` WHERE `list_id`=?");
			$stmt->bind_param('i', $id);
			$result = $stmt->execute();


        	if($result != true)
            	throw new Exception("Couldn't delete items!");
    	}

    	public static function publish($id, $pub, $ml)
		{


			$stmt = $ml->prepare("UPDATE `lists`
                        SET public=?
                        WHERE id=?");
			$stmt->bind_param('ii', $pub, $id);
			$result = $stmt->execute();

			if($result != true)
            	throw new Exception("Couldn't update item!");
		}

		public static function owner_id($id, $ml)
		{

			$stmt = $ml->prepare("SELECT `owner_id` from `lists` WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();

			$result = mysqli_fetch_array($stmt->get_result(),MYSQLI_ASSOC);

			return($result["owner_id"]);


			if($result != true)
            	throw new Exception("Couldn't update item!");

		}
	}


	class Todo
	{

		private $data; // todo data from database

		public function __construct($par){
        	if(is_array($par))
           		$this->data = $par;
   	 	}


		private function checked()
		{
			if($this->data['checked'])
				return "checked";
		}

		public function __toString()
		{
			
			return'
			<li id="todo-'.$this->data['id'].'" class="todo">
				<div class="text">'.htmlspecialchars($this->data['text'], ENT_QUOTES).'</div>
				<div class="desc">'.bbcodeParser($this->data['desc']).'</div>

				<div class="actions">
					<a href="" class="edit">Edit</a>
					<a href="" class="delete">Delete</a>
				<div>
			</li>';
		}

		public static function change($id, $text, $desc, $mysqli)
		{
			if(!$text) 
			{
				throw new Exception("Error Processing Request", 1);
			}

			$stmt = $mysqli->prepare("UPDATE `todos`
                        SET `text`=?,`desc`=?
                        WHERE id=?");
			$stmt->bind_param('ssi',$text,$desc, $id);
			$result = $stmt->execute();

			echo bbcodeParser($desc);

			if($result != true)
            	throw new Exception("Couldn't update item!");
		}

		public static function delete($id, $mysqli){

			$stmt = $mysqli->prepare("DELETE FROM `todos` WHERE id=?");
			$stmt->bind_param('i', $id);
			$result = $stmt->execute();


        	if($result != true)
            	throw new Exception("Couldn't delete item!");

    	}


    	 public static function rearrange($key_value,$mysqli){

        	$updateVals = array();
        	foreach($key_value as $k=>$v)
        	{
            	$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
        	}

        	if(!$strVals) throw new Exception("No data!");

       		// We are using the CASE SQL operator to update the ToDo positions en masse:

        	$result = $mysqli->query("UPDATE `todos` SET position = CASE id
                        ".join($strVals)."
                        ELSE position
                        END");


        	if($result != true)
            	throw new Exception("Error updating positions!");
    	}


    	public static function createNew($text,$desc ,$list ,$mysqli){

	        if(!$text) throw new Exception("Wrong input data!");

	        $list = $mysqli->real_escape_string($list);

	        $posResult = $mysqli->query("SELECT MAX(position)+1 FROM `todos` WHERE `list_id`=".$list);

	        if($posResult->num_rows)
	            list($position) = $posResult->fetch_array();

	        if(!$position) $position = 1;

	        $stmt = $mysqli->prepare("INSERT INTO `todos` SET `text`=?,`desc`=?,`position`=?,list_id=?");
	        $stmt->bind_param('ssii', $text, $desc, $position,$list);
			$result = $stmt->execute();

	        if($mysqli->affected_rows!=1)
	            throw new Exception("Error inserting TODO!");

	        // Creating a new ToDo and outputting it directly:

	        echo (new ToDo(array(
	            'id'	=> $mysqli->insert_id,
	            'text'	=> $text,
	            'desc' => $desc,
	            'checked' => false
	        )));

	        exit;

    	}

    	public static function owner_id($todo ,$mysqli)
    	{
    		$stmt = $mysqli->prepare("SELECT `list_id` from `todos` WHERE id=?");
			$stmt->bind_param('i', $todo);
			$stmt->execute();

			$result = mysqli_fetch_array($stmt->get_result(),MYSQLI_ASSOC);

			return(Lists::owner_id($result["list_id"], $mysqli));


			if($result != true)
            	throw new Exception("Couldn't update item!");

    	}

    	public static function desc($id, $mysqli)
    	{
    		$stmt = $mysqli->prepare("SELECT `desc` from `todos` WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();

			$result = mysqli_fetch_array($stmt->get_result(),MYSQLI_ASSOC);

			
			echo $result["desc"];


			if($result != true)
            	throw new Exception("Couldn't update item!");

    	}

	} 

?>