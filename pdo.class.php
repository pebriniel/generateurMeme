<?php

class SQLpdo {
	function __CONSTRUCT($db=false, $login=false, $pass = false, $adress=false){
		$config['login']=($login) ? $login :'';
		$config['mdp']= ($pass) ? $pass : '';
		$config['adress']=($adress) ? $adress :'127.0.0.1';
		$config['db']=($db) ? $db :'';
		

		try {
		    $this->db = new PDO('mysql:dbname='.$config['db'].';host='.$config['adress'].'', $config['login'], $config['mdp']);
		} catch (PDOException $e) {
		    echo 'Connexion échouée : ' . $e->getMessage();
		}

	}

	function fetchAll($sql,$execute=null){
		$sth = $this->db->prepare($sql);//prepare SQL request
	    $sth->execute($execute);//execute la requette sql
	    return $sth->fetchAll(PDO::FETCH_ASSOC);// recupère toutes les données
	}

	function insert($sql, $execute=null){
		$sth = $this->db->prepare($sql);//prepare SQL request
	    $sth->execute($execute);//execute la requette sql
	    return  $this->db->lastInsertId();// recupère toutes les données
	}

	function fetch($sql,$execute=null){
		$sth = $this->db->prepare($sql);//prepare SQL request
	    $sth->execute($execute);//execute la requette sql
	    return $sth->fetch(PDO::FETCH_ASSOC);// recupère toutes les données
	}
}