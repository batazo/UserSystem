<?php

namespace UserSystem\Components;

use UserSystem\Component;
use UserSystem\Components\DataSource;

/**
 * Class Member
 * @package UserSystem\Components
 */
class Member extends Component
{

    /**
     * @var \UserSystem\Components\DataSource
     */
    private $ds;

    /**
     * Member constructor.
     */
    function __construct()
    {
        $this->ds = new DataSource();
    }

    /**
     * @param $memberId
     * @return array
     */
    function getMemberById($memberId){
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE ID = ?";
        $paramType = "i";
        $paramArray = array($memberId);
        $memberResult = $this->ds->select($query, $paramType, $paramArray);

        return $memberResult;
    }


    /**
     * @param $memberName
     * @return array
     */
    function getMemberByUNAME($memberName){
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE UserName = ?";
        $paramType = "s";
        $paramArray = array($memberName);
        $memberResultByName = $this->ds->select($query, $paramType, $paramArray);

        return $memberResultByName;
    }

    /**
     * @param $memberName
     * @return bool
     */
    function checkMemberExist($memberName){
        $query = "select UserName FROM " . DataSource::USERTABLE . " WHERE UserName = ?";
        $paramType = "s";
        $paramArray = array(trim($memberName));
        $memberResultByName = $this->ds->select($query, $paramType, $paramArray);
        $searchedMember = (isset($memberResultByName[0]['UserName'])) ? $memberResultByName[0]['UserName'] : false;
        return ($searchedMember !== false && $searchedMember === $memberName) ? true : false;
    }

    /**
     * @return array
     */
    function getAllMember(){
        $query = "select * FROM " . DataSource::USERTABLE;
        $AllMemberResult = $this->ds->select($query);

        return $AllMemberResult;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    function registerUser($username, $password){

            $userSecret = rand(100000000, 999999999);
            $userToken = rand(100000000, 999999999);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO " . DataSource::USERTABLE . " (UserName, UserPassword, UserSecret, UserToken) VALUES (?, ?, ?, ?)";
            $paramType = "ssii";
            $paramArray = Array($username, $hashed_password, $userSecret, $userToken);
            if($this->ds->insert($query, $paramType, $paramArray)){
				return true;
			} else { return false; }
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function processLogin($username, $password) {
		$queryUserPassworldHash = "select UserPassword, UserName FROM " . DataSource::USERTABLE . " WHERE UserName = ?";
		$paramTypeForUP = "s";
		$paramArrayForUP = array($username);
		$userPassworldHashResult = $this->ds->select($queryUserPassworldHash, $paramTypeForUP, $paramArrayForUP);

		$passwordHash = (isset($userPassworldHashResult[0]['UserPassword'])) ? $userPassworldHashResult[0]['UserPassword'] : "zero";
        $queryedUser = (isset($userPassworldHashResult[0]['UserName'])) ? $userPassworldHashResult[0]['UserName'] : "ThisDoesnotExist";

		if (password_verify($password, $passwordHash) && $username === $queryedUser) {
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
        } else {

			return false;
		}
          } else {
               return false;
         }

    }
}
