<?php

namespace UserSystem\Components;

use UserSystem\Component;
use \mysqli;

/**
 * Class DataSource
 * @package UserSystem\Components
 */
class DataSource extends Component
{
    /**
     *
     */
    const USERTABLE = 'Users';
    /**
     * @var mysqli
     */
    private $conn;


    /**
     * DataSource constructor.
     */
    function __construct()
    {
		if(!isset($wehaveconn)){
        $this->conn = $this->getConnection();
		$wehaveconn = 1;
        }
	}

    /**
     * Todo: Recalibrate file path
     * @return mysqli
     */
    public function getConnection()
    {
		$dbinipath = realpath(__DIR__ . "/../../db-config.ini");
		$dbconf = parse_ini_file($dbinipath);
        $conn = new \mysqli($dbconf['host'], $dbconf['dbuser'], $dbconf['dbpass'], $dbconf['dbname']);

        if (mysqli_connect_errno()) {
            trigger_error("Problem with connecting to database.");
        }

        $conn->set_charset("utf8");
        return $conn;
    }


    /**
     * @param $query
     * @param string $paramType
     * @param array $paramArray
     * @return array
     */
    public function select($query, $paramType="", $paramArray=array())
    {
        $stmt = $this->conn->prepare($query);

        if(!empty($paramType) && !empty($paramArray)) {
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        if (! empty($resultset)) {
            return $resultset;
        }
    }


    /**
     * @param $query
     * @param $paramType
     * @param $paramArray
     * @return int
     */
    public function insert($query, $paramType, $paramArray)
    {
        $stmt = $this->conn->prepare($query);
        $this->bindQueryParams($stmt, $paramType, $paramArray);
        $stmt->execute();
        $insertId = $stmt->insert_id;
        return $insertId;
    }


    /**
     * @param $query
     * @param $paramType
     * @param $paramArray
     * @return mixed
     */
    public function del($query, $paramType, $paramArray)
    {
        $stmt = $this->conn->prepare($query);
		//$stmt->bind_param($paramType, $paramArray);
        $this->bindQueryParams($stmt, $paramType, $paramArray);
        $stmt->execute();
        $delId = $stmt->del_id;
        return $delId;
    }


    /**
     * @param $query
     * @param string $paramType
     * @param array $paramArray
     */
    public function execute($query, $paramType="", $paramArray=array())
    {
        $stmt = $this->conn->prepare($query);

        if(!empty($paramType) && !empty($paramArray)) {
            $this->bindQueryParams($stmt, $paramType="", $paramArray=array());
        }
        $stmt->execute();
    }


    /**
     * @param $stmt
     * @param $paramType
     * @param array $paramArray
     */
    public function bindQueryParams($stmt, $paramType, $paramArray=array())
    {
        $paramValueReference[] = & $paramType;
        for ($i = 0; $i < count($paramArray); $i ++) {
            $paramValueReference[] = & $paramArray[$i];
        }
        call_user_func_array(array(
            $stmt,
            'bind_param'
        ), $paramValueReference);
    }


    /**
     * @param $query
     * @param string $paramType
     * @param array $paramArray
     * @return int
     */
    public function numRows($query, $paramType="", $paramArray=array())
    {
        $stmt = $this->conn->prepare($query);

        if(!empty($paramType) && !empty($paramArray)) {
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }

        $stmt->execute();
        $stmt->store_result();
        $recordCount = $stmt->num_rows;
        return $recordCount;
    }
}
