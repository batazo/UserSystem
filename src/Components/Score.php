<?php

namespace UserSystem\Components;
use UserSystem\Component;

/**
 * Class Score
 * @package UserSystem\Components
 */
class Score extends Component
{

    /**
     * @var DataSource
     */
    private $ds;

    /**
     * Score constructor.
     */
    function __construct()
    {
        $this->ds = new DataSource();
    }


    /**
     * @param $userName
     * @return array
     */
    function getScoreByUserName($userName)
    {
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE UserName = ?";
        $paramType = "s";
        $paramArray = array($userName);
        $scoreResultByUNAME = $this->ds->select($query, $paramType, $paramArray);
        //echo "<pre>";
        //var_dump($scoreResultByUNAME);
        return $scoreResultByUNAME;
    }


    /**
     * @param $userId
     * @return array
     */
    function getScoreByUserId($userId)
    {
        $query = "select * FROM " . DataSource::USERTABLE . " WHERE ID = ?";
        $paramType = "i";
        $paramArray = array($userId);
        $scoreResultByUID = $this->ds->select($query, $paramType, $paramArray);

        return $scoreResultByUID;
    }


    /**
     * @return array
     */
    function getAllUserScore()
    {
        $query = "select * FROM " . DataSource::USERTABLE;
        $scoreResult = $this->ds->select($query);
        return $scoreResult;
    }

}
