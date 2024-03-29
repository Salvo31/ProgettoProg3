<?php //File back-end relativo a tutte le interazioni col DB
  require "connection.php";
  //Ricavo informazioni dalla richiesta. Tra cui un eventuale file nel body
  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));//Creo un array contenente tutti i parametri dell'URI
  $input = json_decode(file_get_contents('php://input'),true);


  $primoParametro = preg_replace('/[^a-z0-9_]+/i','',array_shift($request)); //Primo parametro passato nell'URI
  $_key = array_shift($request);
  $key = $_key; //Secondo parametro passato nell'URI

  //Divido le operazioni in base alla richiesta HTTP
  if($method == 'GET'){
    $res;//Variabile che salva il risultato della query eseguita
    switch($primoParametro){//In base al primo parametro varia la query
      case 'AMRAP':
        $sql = "select IdEsercizio,MinRep,MaxRep,Nome from Esercizio where tipologia='$key' ";
        $res = elaborateQuery($sql,$conn);
        $res = randomAmrap($res);
        break;
      case 'EMOM':
      case 'SCHEDA':
        $intervallo = array_shift($request);//In questo caso è presente un terzo parametro
        $sql = "select IdEsercizio,MinRep,MaxRep,Nome from Esercizio where tipologia='$key' ";
        $res = elaborateQuery($sql,$conn);
        $res = randomExercise($res,$intervallo);
        break;
      case 'allenamenti':
        $sql = "select ag.*
        from utente u inner join svolgimento s on u.IdUtente = s.Utente
        inner join allenamentogenerato ag on s.AllenamentoGenerato = ag.IdWorkoutGen
        where u.IdUtente = $key ";
        $res = elaborateQuery($sql,$conn);
        $res = $res->fetch_All(MYSQLI_ASSOC);
        break;
      case 'eserciziAllenamento':
        $sql = "select cw.NumRep,cw.NumSets,e.Nome,e.Tipologia
        from allenamentogenerato ag inner join composizionewe cw on ag.IdWorkoutGen = cw.AllenamentoGenerato
        inner join esercizio e on cw.Esercizio = e.IdEsercizio
        where ag.IdWorkoutGen = '$key'";
        $res = elaborateQuery($sql,$conn);
        $res = $res->fetch_All(MYSQLI_ASSOC);
    }
    $json = json_encode($res); //Tutti i risultati vengono formattati in JSON
    echo $json;
  }
  else if($method == 'POST'){//Questo gestisce le caistiche del metodo POST
    $res;
    switch($primoParametro){
      case 'login':
        $sql = "select Nome,Cognome,IdUtente from utente where email = '$input[email]' and password = '$input[password]' ";
        $res = elaborateQuery($sql,$conn);
        echo returnFromLogin($res);
        break;
      case 'register':
        $sql = "Insert into utente (Nome,Cognome,Password,Email) Values ('$input[nome]','$input[cognome]','$input[password]','$input[email]')";
        $res = $conn->query($sql);
        if($res == true){
          echo json_encode(["success" => true]);
        }
        else{
          echo json_encode(["success" => false]);
        }
        break;
      case 'allenamentogenerato': //Questo caso in particolare, gestisce l'inserimento di un allenamento completato
        $response = array(); //I risultati degli inserimenti andranno tutti in questo array
        $IdWorkoutGen = uniqid(); //Id univoco da 13 caratteri, generato da php
        $Data = date("Y-m-d");
        $i = 0;
        $sql = "Insert Into allenamentogenerato (IdWorkoutGen,Data,Tipo,Durata) Values ('$IdWorkoutGen','$Data','$input[Tipo]',$input[Durata])";
        $response = array_merge($response,insertWorkout($primoParametro,$sql,$conn));
        $sql = "Insert Into svolgimento (Utente,AllenamentoGenerato) Values ($input[UserId],'$IdWorkoutGen')";
        $response = array_merge($response,insertWorkout("svolgimento",$sql,$conn));
        $keys = array_keys($input);
        while(gettype($keys[$i]) != "string"){
          $ripetizioni = $input[$i]['reps'];
          $esercizio = $input[$i]['IdEsercizio'];
          if($input['Tipo'] == "SCHEDA"){
            $serie = $input[$i]['serie'];
            $sql = "Insert Into composizionewe (NumRep,NumSets,AllenamentoGenerato,Esercizio) Values ($ripetizioni,$serie,'$IdWorkoutGen',$esercizio)";
          }
          else{
            $serie = 0;
            $sql = "Insert Into composizionewe (NumRep,NumSets,AllenamentoGenerato,Esercizio) Values ($ripetizioni,$serie,'$IdWorkoutGen',$esercizio)";
          }
          $response = array_merge($response,insertWorkout("composizionewe",$sql,$conn,$i));
          $i++;
        }
        echo json_encode($response);
        break;
    }
  }
  else if($method == "DELETE"){//Unico caso col metodo delete, è l'eliminazione di un workout
    //Prima elimino i dati nelle tabelle relazionate con la query in sqlRelateTable e successivamente elimino il record dalla principale
    $res;
    if($primoParametro == "eliminaWorkout"){
      $response = array();
      $sqlMainTable = "delete from allenamentogenerato where IdWorkoutGen = '$key' ";
      $sqlRelateTable = "delete from composizionewe where AllenamentoGenerato = '$key' ";
      $res = elaborateQuery($sqlRelateTable,$conn);
      if($res){
          $response = array_merge($response,["composizionewe" => true]);
      }
      else{
        $response = array_merge($response,["composizionewe" => false]);
      }
      $sqlRelateTable = "delete from svolgimento where AllenamentoGenerato = '$key' ";
      $res = elaborateQuery($sqlRelateTable,$conn);
      if($res){
          $response = array_merge($response,["svolgimento" => true]);
      }
      else{
        $response = array_merge($response,["svolgimento" => false]);
      }
      $res = elaborateQuery($sqlMainTable,$conn);
      if($res){
          $response = array_merge($response,["allenamentogenerato" => true]);
      }
      else{
        $response = array_merge($response,["allenamentogenerato" => false]);
      }
      echo json_encode($response);
    }
  }

  function elaborateQuery($query,$conn){//Funzione che esegue la query
    $result = $conn->query($query);
    if($result){
      return $result;
    }
    else{
      print_r($conn->error);
    }
  }

  function insertWorkout($tabella,$sql,$conn,$i = -1){//Funzione di inserimento. Il parametro i serve per la tabella composizioneWe in modo da tracciarne tutti gli inserimenti
    $res;
    try{
        $res = $conn->query($sql);
        if($i == -1){
          if($res == true){
            return ["$tabella" => true];
          }
          else{
            return ["$tabella" => false];
          }
        }
        else{
          if($res == true){
            return ["Esercizio".$i+1 => true];
          }
          else{
            return ["Esercizio".$i+1 => false];
          }
        }
    }
    catch (Exception $e){
      print_r($e->getMessage());
      return 0;
    }
  }

  function returnFromLogin($res){//Costruisco un array che diventerà il JSON di risposta con un semplice parametro per l'ACK al login
    $rows = $res->fetch_object();
    if($rows){
      $rows->success = true; //Qui aggiungo la proprietà success ai dati già estrapolati
    }
    else{
      $rows = ["success" => false]; //Invece qui svuoto il risultato ritornando solo un array con success = false
    }
    return json_encode($rows);
  }

  /* --- Queste ultime 2 funzioni servono a randomizzare la scelta degli esercizi che possono capitare
  E' stata "installata" la libreria DS sul server, in modo da poter utilizzare l'oggetto set che è un insieme matematico.
  Tutto ciò è fatto per aggiungere correttamente gli esercizi senza ripetizioni.
  La differenza è che quella relativa all'AMRAP sceglie anche il numero di esercizi, che parte da 3 sino a 10
  --- */
  function randomExercise($res,$intervallo){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_ASSOC);
    $set = new \Ds\Set();
    do{ //il do while è inserito per assicurarmi che il set venga riempito sino al numero di esercizi richiesti
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }while($set->count() < $intervallo);
    return $set;
  }

  function randomAmrap($res){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_ASSOC);
    $numEs = rand(3,10);
    $set = new \Ds\Set();
    for($i=0;$i<$numEs;$i++){
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }
    return $set;
  }

$conn = null; //chiusura della connessione
 ?>
