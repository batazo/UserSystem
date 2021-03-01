<?php
namespace Usersystem;
use \Usersystem\DataSource;

class Score
{


    private $ds;

    function __construct()
    {
        require_once "DataSource.php";
        $this->ds = new DataSource();
    }


	function getScoreByUserName($userName) {
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE UserName = ?";
        $paramType = "s";
        $paramArray = array($userName);
        $scoreResultByUNAME = $this->ds->select($query, $paramType, $paramArray);
        //echo "<pre>";
		//var_dump($scoreResultByUNAME);
        return $scoreResultByUNAME;
    }
    
	
	function getScoreByUserId($userId) {
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE ID = ?";
        $paramType = "i";
        $paramArray = array($userId);
        $scoreResultByUID = $this->ds->select($query, $paramType, $paramArray);
        
        return $scoreResultByUID;
    }
	

	
		function getAllUserScore(){
        $query = "select * FROM " . DataSource::USERTABLE;
        $scoreResult = $this->ds->select($query);
        return $scoreResult;
    }

}