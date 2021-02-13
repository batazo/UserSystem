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

    function getMemberById($memberId)
    {
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE ID = ?";
        $paramType = "i";
        $paramArray = array($memberId);
        $memberResult = $this->ds->select($query, $paramType, $paramArray);
        
        return $memberResult;
    }
	
	function getAllMember()
    {
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
            return true;
        }
    }
}