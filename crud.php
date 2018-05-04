<?php 

	class crud{
		private $pdo;
		private $table;

		public function __construct($table,$host,$database,$user,$password){
			$this->table = $table;
			$this->pdo = new PDO("mysql:host=".$host.";dbname=".$database,$user,$password);

		}

		private function sqlInsert($dados){
			$sql = '';
			$campos = '';
			$valores = '';

			foreach ($dados as $key => $valor) {
				$campos .= $key.', ';
				$valores .= '?, ';

			}

			if(substr($campos, -2) == ", "){
				$campos = trim(substr($campos,0,(strlen($campos) -2)));
				$valores = trim(substr($valores,0,(strlen($valores) -2)));

			}
			else{
				$campos = $campos;
				$valores = $valores;
			}

			$sql = "INSERT INTO ".$this->table." (".$campos.") VALUES (".$valores.");";

			return trim($sql);

		}
		private function sqlDelete($dados){

			$sql = '';
			$campos = '';

			foreach ($dados as $key => $value) {
				$campos .= $key.'= ? AND ';
			}

			if(substr($campos, -4) == "AND "){
				$campos = trim(substr($campos,0,(strlen($campos) -4)));
			}
			else{
				$campos = $campos;
			}

			$sql = "DELETE FROM ".$this->table." WHERE ".$campos;

			return trim($sql);

		}

		private function sqlUpdate($dados,$chave){

			$sql = '';
			$campos = '';
			$camposChave = '';


			foreach ($dados as $key => $value) {
				$campos .= $key.' = ?, ';
			}
			foreach ($chave as $key => $value) {
				$camposChave .=$key. '= ? AND ';
			}

			if(substr($campos, -2) == ', '){
				$campos = trim(substr($campos,0,(strlen($campos)) -2));
			}
			if (substr($camposChave, -4) == 'AND '){
				$camposChave = trim(substr($camposChave,0,(strlen($camposChave)) - 4));
			}
			else{
				$campos = $campos;
				$camposChave = $camposChave;
			}
			$sql = "UPDATE ".$this->table." SET ".$campos." WHERE ".$camposChave;

			return trim($sql);

		}


		public function insert($dados){

			$stmt = $this->pdo->prepare($this->sqlInsert($dados));
			$contador = 1;

			foreach($dados as $value){
				$stmt->bindValue($contador,$value);
				$contador++;
			}
			$stmt->execute();

		}
		public function delete($dados){
			$stmt = $this->pdo->prepare($this->sqlDelete($dados));
			$contador = 1;

			foreach ($dados as $value) {
				$stmt->bindValue($contador,$value);
				$contador++;
			}
			$stmt->execute();

		}
		public function update($dados,$chave){

			$stmt = $this->pdo->prepare($this->sqlUpdate($dados,$chave));
			$contadorDados = 1;

			foreach ($dados as $value) {
				$stmt->bindValue($contadorDados,$value);
				$contadorDados++;
			}
			foreach ($chave as $var) {
				$stmt->bindValue($contadorDados,$var);
				$contadorDados++;
			}
			$stmt->execute();

		}
		public function select(){

			$stmt = $this->pdo->prepare("SELECT * FROM ".$this->table);
			$stmt->execute();
			
			while($row = $stmt->fetch()){
				print_r($row);
			}
			
		}


	}





?>
