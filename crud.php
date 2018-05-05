<?php 

	class crud{
		private $pdo;
		private $table;

		public function __construct($host,$database,$user,$password){
			$this->pdo = new PDO("mysql:host=".$host.";dbname=".$database,$user,$password);

		}


		private function sqlInsert($dados,$tabela){
			$sql = '';
			$campos = '';
			$valores = '';
			$this->table = $tabela;

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

		private function sqlSelect(){
		}

		private function sqlDelete($dados,$tabela){

			$sql = '';
			$campos = '';
			$this->table = $tabela;

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

		private function sqlUpdate($dados,$chave,$tabela){

			$sql = '';
			$campos = '';
			$camposChave = '';
			$this->table = $tabela;


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


		public function insert($dados,$tabela){

			$stmt = $this->pdo->prepare($this->sqlInsert($dados,$tabela));
			$contador = 1;

			foreach($dados as $value){
				$stmt->bindValue($contador,$value);
				$contador++;
			}
			$stmt->execute();

		}
		public function delete($dados,$tabela){
			$stmt = $this->pdo->prepare($this->sqlDelete($dados,$tabela));
			$contador = 1;

			foreach ($dados as $value) {
				$stmt->bindValue($contador,$value);
				$contador++;
			}
			$stmt->execute();

		}
		public function update($dados,$chave,$tabela){

			$stmt = $this->pdo->prepare($this->sqlUpdate($dados,$chave,$tabela));
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
		public function select($tabela){
			$this->table = $tabela;
			$stmt = $this->pdo->prepare("SELECT * FROM ".$this->table);
			$stmt->execute();
			
			while($row = $stmt->fetch()){
				print_r($row);
			}
			
		}


	}





?>