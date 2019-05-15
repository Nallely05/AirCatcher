<?php
	$action = $_POST['action'];
    switch($action)
    {
        case "agregarPartida":
            agregarPartida();
        break;
        case "obtenerPuntuaciones":
            obtenerPuntuaciones();
        break;
    } 
	
	function connect() {
		$databasehost = "twicky.com.mx";
		$databasename = "db_hispanofic";
		$databaseuser = "db_hispanofic";
		$databasepass = "db_hispanofic";
		$mysqli = new mysqli($databasehost, $databaseuser, $databasepass, $databasename);
		if ($mysqli->connect_errno) {
			echo "Problema con la conexion a la base de datos";
		}
		return $mysqli;
	}
	function disconnect() {
		mysqli_close();
    }
 
	function agregarPartida() {
		$gamename_01 = $_POST["gamename_01"];
        $gamename_02 = $_POST["gamename_02"];
        $nivel = $_POST["nivel"];
		$gamepoints_01 = $_POST["gamepoints_01"];
        $gamepoints_02 = $_POST["gamepoints_02"];
		$mysqli = connect();
        
        $result = $mysqli->query("insert into tbl_partida (jugador1, jugador2, puntuacion1, puntuacion2, nivel) values ('".$gamename_01."','".$gamename_02."',".$gamepoints_01.",".$gamepoints_02.",".$nivel.");");
        
        
		if (!$result) {
           $NoEcho = "Problema al hacer un query: " . $mysqli->error;
            $rows = array();			
            array_push($rows,$NoEcho);
            echo json_encode($rows);
        } 
        else {
            $NoEcho = "OK";
            $rows = array();			
            array_push($rows,$NoEcho);
            echo json_encode($rows);
		}
		mysqli_close($mysqli);
    }
    function obtenerPuntuaciones()
    {
        $mysqli = connect();
		$result = $mysqli->query("select* from tbl_partida  order by idPartida desc limit 5;");
		
		if (!$result) {
			echo "Problema al hacer un query: " . $mysqli->error;								
		} else {
			// Recorremos los resultados devueltos
			$rows = array();
			while( $r = $result->fetch_assoc()) {
				$rows[] = $r;
			}			
			// Codificamos los resultados a formato JSON y lo enviamos al HTML (Client-Side)
			
			echo json_encode($rows);
		}
		mysqli_close($mysqli);		
    }
