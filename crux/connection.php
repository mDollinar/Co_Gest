<?php
require_once "constants.php";


function duplicateArray($array){
	$ret = [];
	for($i=0; $i<count($array); $i++){
		if(is_array($array[$i])) array_push($ret, duplicateArray($array[$i]));
		else array_push($ret, $array[$i]);
	}
	return $ret;
}

function duplicateAssoArray($array){
	$ret = array();
	foreach( $array as $key => $value){
		if(is_array($array[$key])) array_push($ret, duplicateArray($array[$key]));
		else $ret[$key] = $value;
	}
	return $ret;
}

function generate_string($input, $strength = 16) {
	$input_length = strlen($input);
	$random_string = '';
	for($i = 0; $i < $strength; $i++) {
		$random_character = $input[mt_rand(0, $input_length-1)];
		$random_string .= $random_character;
	}

	return $random_string;
}

	//classe per la gestione della connessione con il database
	class DBconn{
		protected $conn;
		
		//lego alla costruzione la connessione
		public function __construct(){
			$this->conn = mysqli_connect(servername, username, password,database);
			
			if(!isset($this->conn)) die("Connessione fallita: ". mysqli_connect_error());
			return $this->conn;
		}
		//lego alla distruzione la disconnessione
		public function __destruct(){
			mysqli_close($this->conn);
		}
	}
	
	//classe per l'esecuzione di query sul DB
	class Query extends DBconn{
		protected $conn;
		protected $query;
		public $results = array(); //pubblica per permettermi di passare un qualunque risultato come parametro di altre query
		
		//funzione per la verifica che una query abbia dato dei risultati
		public function checkResults(){
			return count($this->results)>0;
		}
		
		//funzione di sviluppo per controllare la query
		public function printQuery(){
			echo "<br>";
			var_dump($this->query);
		}
		
		//funzione di sviluppo per il controllo dei risultati
		public function printResults(){
			print_r($this->results);
			echo "<br /><br />";
			var_dump($this->results);
			echo "<br /><br />";

		}
		//funzione per gestire alternativamente i risultati
		public function retResults(){
			return $this->results;
		}
		
		//funzione per la restituzione dei risultati già in JSON format
		public function jsonResults(){
			return json_encode($this->retResults());
		}
		
		//funzione per la stampa dei risultati già in JSON format
		public function printJSON(){
			echo json_encode($this->retResults());
		}
		
		//funzione per la preparazione di un campo testo che vada preparato per l'inserimento nel DB
		public function setText($text){
			return mysqli_real_escape_string($this->conn, $text);
		}
		
		//funzione per il reset della query e dei risultati
		public function reset(){
			$this->query = "";
			$this->results = array();
		}
		
		//funzione per la copia di un record da una tabella ad un'altra
		public function copy($from, $to, $where, $more_fields=null, $last_id = null){
			$this->query = "INSERT INTO $to SELECT ";
			
			if(isset($more_fields)){
			
				$this->query .= $from.".*, ".$more_fields[0];
				if(count($more_fields) > 1){
					for($i = 1; $i < count($more_fields); $i++){
						$this->query .= ", ".$more_fields[$i];
					}
				}
			}else{
				$this->query .= "*";
			}
			
			$this->query .= " FROM $from WHERE $where";
			
			$check = mysqli_query($this->conn,$this->query);
			if(!$check) print "Query errata: ".mysqli_error($this->conn). " Query: ".$this->query;
			if($last_id == true) {
				$this->results['last_id'] = mysqli_insert_id($this->conn);
			}
			return false;
		}
		
		/*selezione/proiezione e restituzione di record dal db*/
		public function select($asso, $what, $from, $where=null, $order=null, $limit=null, $offset=null){
		
			/*costruzione della query*/
			$this->query = "SELECT $what FROM $from";
			if(isset($where) && strlen($where)>0) $this->query .= " WHERE $where";
			if(isset($order)) $this->query .= " ORDER BY $order";
			if(isset($limit)){
				$this->query .= " LIMIT $limit";
				if(isset($offset)){
					$this->query .= " OFFSET $offset";
				}
			$this->query .=";";
			}
			
			/*esecuzione della query*/
			$res = mysqli_query($this->conn, $this->query);
			
			if(!$res){
				print "Query fallita: ".mysqli_error($this->conn). " Query: ".$this->query;
				return false;
			}
			
			/*preparazione dei valori da restituire*/
			if($asso){
				while($rec = mysqli_fetch_array($res, MYSQLI_ASSOC)) array_push($this->results, $rec);
			}elseif($asso == "both"){
				while($rec = mysqli_fetch_array($res, MYSQLI_BOTH)) array_push($this->results, $rec);
			}else{
				while($rec = mysqli_fetch_array($res, MYSQLI_NUM)) array_push($this->results, $rec);
			}
			mysqli_free_result($res);
		}

		/*selezione/proiezione e restituzione di record dal db, aggiungendo nuovi array a ogni interazione */
		public function multiSelect($what, $from, $where=null, $order=null, $limit=null, $offset=null){

			/*costruzione della query*/
			$this->query = "SELECT $what FROM $from";
			if(isset($where) && strlen($where)>0) $this->query .= " WHERE $where";
			if(isset($order)) $this->query .= " ORDER BY $order";
			if(isset($limit)){
				$this->query .= " LIMIT $limit";
				if(isset($offset)){
					$this->query .= " OFFSET $offset";
				}
				$this->query .=";";
			}

			/*esecuzione della query*/
			$res = mysqli_query($this->conn, $this->query);

			if(!$res){
				print "Query fallita: ".mysqli_error($this->conn). " Query: ".$this->query;
				return false;
			}
			$r = array();
			/*preparazione dei valori da restituire*/
			while($rec = mysqli_fetch_array($res)) array_push($r, $rec);
			array_push($this->results, $r);
			mysqli_free_result($res);
		}
		
		/*selezione/proiezione e restituzione di record dal db a seguito di una query già eseguita*/
		public function subSelect($position, $what, $from, $where=null, $order=null, $limit=null){
			for($i = 0; $i < count($this->results); $i++){
				
				$this->results[$i][$position] = array();
				
				/*costruzione della query*/
				$this->query = "SELECT $what FROM $from";
				if(isset($where)) $this->query .= " WHERE $where";
				if(isset($order)) $this->query .= " ORDER BY $order";
				if(isset($limit)){
					$this->query .= " LIMIT $limit";
				}
				$this->query .=";";
				
				/*esecuzione della query*/
				$res = mysqli_query($this->conn, $this->query);
				
				if(!$res){
					print "Query fallita: ".mysqli_error($this->conn). " Query: ".$this->query;
					return false;
				}
				
				/*preparazione dei valori da restituire*/
				while($rec = mysqli_fetch_array($res)) array_push($this->results[$i][$position], $rec);
				mysqli_free_result($res);
			}
		}

        /*selezione/proiezione e restituzione di record dal db a seguito di una query già eseguita*/
        public function subSelectOnField($position, $what, $from, $db_field_from, $res_field,  $order=null, $limit=null, $whereAdd=null){
            for($i = 0; $i < count($this->results); $i++){

                $this->results[$i][$position] = array();

                /*costruzione della query*/
                $this->query = "SELECT $what FROM $from";
                if(isset($db_field_from) && isset($res_field)) $this->query .= " WHERE $db_field_from = '".$this->results[$i][$res_field]."'";
				if(isset($whereAdd)) $this->query .= " $whereAdd";
                if(isset($order)) $this->query .= " ORDER BY $order";
                if(isset($limit)){
                    $this->query .= " LIMIT $limit";
                }
                $this->query .=";";

                /*esecuzione della query*/
                $res = mysqli_query($this->conn, $this->query);

                if(!$res){
                    print "Query fallita: ".mysqli_error($this->conn). " Query: ".$this->query;
                    return false;
                }

                /*preparazione dei valori da restituire*/
                while($rec = mysqli_fetch_array($res)) array_push($this->results[$i][$position], $rec);
                mysqli_free_result($res);
            }
        }
		
		/*inserimento valori nel db*/
		public function insert($table, $values, $last_id = false){
			$this->query = "INSERT INTO $table($values[0]) VALUES ($values[1])";
			if(count($values)>2){
				for($i = 2; $i<count($values); $i++){
					$this->query .= ", ($values[$i])";
				}
			}
			$check = mysqli_query($this->conn,$this->query);
			if(!$check) print "Query errata: ".mysqli_error($this->conn). " Query: ".$this->query;
			if($last_id == true){
				$this->results['last_id'] = mysqli_insert_id($this->conn);
			}else{
				return false;
			}
		}
		
		/*esecuzione di una funzione prestabilita di sql e restituzione dei valori calcolati dai record dal db*/
		public function funct($what, $from, $funct, $where=null, $order=null, $limit=null, $offset=null){
		
			/*costruzione della query*/
			$this->query = "SELECT $funct($what) AS $what FROM $from";
			if(isset($where)) $this->query .= " WHERE $where";
			if(isset($order)) $this->query .= " ORDER BY $order";
			if(isset($limit)){
				$this->query .= " LIMIT $limit";
				if(isset($offset)){
					$this->query .= " OFFSET $offset";
				}
			$this->query .=";";
			}
			
			/*esecuzione della query*/
			$res = mysqli_query($this->conn, $this->query);
			
			if(!$res){
				print "Query fallita: ".mysqli_error($this->conn). " Query: ".$this->query;
				return false;
			}
			
			/*preparazione dei valori da restituire*/
			while($rec = mysqli_fetch_array($res)) array_push($this->results, $rec);
			mysqli_free_result($res);
		}
		
		/*aggiornamento valori nel db*/
		public function update($table, $couples, $where=null){
			$this->query = "UPDATE $table SET $couples[0]";
			if(count($couples)>1){
				for($i = 1; $i<count($couples); $i++){
					$this->query .= ", $couples[$i]";
				}
			}
			
			if(isset($where)) $this->query .=  " WHERE $where";
			
			$check = mysqli_query($this->conn, $this->query);
			if(!$check) print "Query errata: ".mysqli_error($this->conn). " Query: ".$this->query;
			return false;
		}
		
		//Cancellazione di un valore da una tabella
		public function delete($table, $where){
			$this->query = "DELETE FROM $table WHERE $where";
			
			$check = mysqli_query($this->conn, $this->query);
			if(!$check) print "Query errata: ".mysqli_error($this->conn). " Query: ".$this->query;
			return false;
		}
		
		/*Query non strutturata*/
		public function anyQuery($query){
			$this->query = $query;
			
			/*realizzazione della query*/
			$res = mysqli_query($this->conn, $this->query);
			
			if(!$res){
				print "Query fallita: ".mysqli_error($this->conn). " Query: ".$this->query;
				return false;
			}
			
			/*preparazione dei valori da restituire*/
			while($rec = mysqli_fetch_array($res)) array_push($this->results, $rec);
			mysqli_free_result($res);
		}
	}

	//classe per funzioni evolute di Query
	class MultiFunction extends Query{
		public function uploadFile($edit, $dir, $inputName, $DBTable, $DBField, $fileName, $oldFile = null){
			$ext = explode(".", $_FILES[$inputName]['name']);
			$ext = $ext[count($ext) - 1];
			if(!isset($fileName)) {
				$fileName = $this->keyGenerator($DBTable, $DBField);
				$fileName .= "." . $ext;
			}
			$error_list = [];
			$message = array();
			$target_dir = $dir;
			$target_file = $target_dir . $fileName;
			$uploadOk = 1;
			$imageFileType = $ext;
			// Check if file already exists;
			if (file_exists($target_file)) {
				$this->renameFile($target_file, true, $DBTable, $DBField, $DBField_list);
			}
			// Check file size
			if ($_FILES[$inputName]["size"] > 2*MB) {
				array_push($error_list, "Il file supera la dimensione massima di 2MB");
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" && $imageFileType != "pdf" ) {
				array_push($error_list, "Il file non &egrave; del tipo supportato. Sono permessi solo file JPG, PNG, JPEG, GIF e PDF.");
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$message['text'] = "Ci dispiace, ma abbiamo rilevato i seguenti errori:";
				$message['errori'] = $error_list;
				return $message;
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $target_file)) {
					$message['text'] = "Il file ". $fileName. " &egrave; stato correttamente caricato.";
					if($edit) unlink($dir.$oldFile);
				} else {
					$message['text'] = "Ci dispiace ma c&apos;&egrave; stato un problema con l&apos;upload, ti preghiamo di riprovare pi&ugrave; tardi.";
				}
				return $message;
			}
		}

		public function renameFile($file, $auto = false, $DBTable, $DBField, $newName = null){
			$pathinfo = pathinfo($file);

			if($auto) {
				$autoName = $this->keyGenerator($DBTable, $DBField);
				$autoName .= ".".$pathinfo['extension'];
				$autoName_dir = $pathinfo['dirname'] . "/" .$autoName;
				rename($file, $autoName_dir);
				$c = ["$DBField = '$autoName'"];
				$this->update($DBTable, $c, "id = " . $this->results[0]['id']);
			}else{
				rename($file, $newName);
				$c = ["$DBField = '$newName'"];
				$this->update($DBTable, $c, "id = " . $this->results[0]['id']);
			}
		}

        public function keyGenerator($table, $field){
			global $permitted_chars;
            $this->reset();
            $key = generate_string($permitted_chars, 20);

            $this->funct($field, $table, "COUNT", "$field = '$key'");
            if($this->results[0][0] == 0){
                $this->addLog("generato la chiave ".$key, true);
                return $key;
            }
            else{
            	return $this->keyGenerator($table, $field);
            }
        }
        public function keyFileName ($name, $table, $field){
			$ext = explode(".", $name);
			$ext = $ext[count($ext)-1];
			$name = $this->keyGenerator($table, $field);
			$name .= ".".$ext;
			$this->reset();
			return $name;
		}
	}
?>
