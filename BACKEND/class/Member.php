<?php
namespace Usersystem;

use \Usersystem\DataSource;

class Member
{

    private $dbConn;

    private $ds;

    function __construct()
    {
        require_once "DataSource.php";
        $this->ds = new DataSource();
    }

    function getMemberById($memberId){
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE ID = ?";
        $paramType = "i";
        $paramArray = array($memberId);
        $memberResult = $this->ds->select($query, $paramType, $paramArray);
        
        return $memberResult;
    }
	
	
    function getMemberByUNAME($memberName){
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE UserName = ?";
        $paramType = "s";
        $paramArray = array($memberName);
        $memberResultByName = $this->ds->select($query, $paramType, $paramArray);
        
        return $memberResultByName;
    }
	
	function getAllMember(){
        $query = "select * FROM " . DataSource::USERTABLE;
        $AllMemberResult = $this->ds->select($query);
        
        return $AllMemberResult;
    }
    
    public function processLogin($username, $password) {
        $passwordHash = md5($password);
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE UserName = ? AND UserPassword = ?";
        $paramType = "ss";
        $paramArray = array($username, $passwordHash);
        $memberResult = $this->ds->select($query, $paramType, $paramArray);
        if(!empty($memberResult)) {
			$_SESSION["UserID"] = $memberResult[0]["ID"];
			$_SESSION["UserName"] = $memberResult[0]["UserName"];
			$_SESSION["UserSecret"] = $memberResult[0]["UserSecret"];
			$_SESSION["UserToken"] = $memberResult[0]["UserToken"];
            return true;
        }
    }
}