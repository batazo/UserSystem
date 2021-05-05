<?php
class Request {
	
	public $postLoginName;
	public $postLoginPassw;
	public $postRegName;
	public $postRegPassw;
	
	function __construct(){
		$this->postLoginName = (isset($_POST['loginName'])) ? $_POST['loginName'] : false;
		$this->postLoginPassw = (isset($_POST['loginPassw'])) ? $_POST['loginPassw'] : false;
		$this->postRegName = (isset($_POST['regName'])) ? $_POST['regName'] : false;
		$this->postRegPassw = (isset($_POST['regPassw'])) ? $_POST['regPassw'] : false;
	}	
	
	public function requestHandler(){
		if($this->postLoginName and $this->postLoginPassw){
			return $this->loginReq();
		}
		
		if($this->postRegName and $this->postRegPassw){
			return $this->registerReq();
		}
	}
	
	public function loginReq(){
		return ['loginName' => $this->postLoginName, 'loginPassw' => $this->postLoginPassw];
	}
	
	public function registerReq(){
		return ['regName' => $this->postRegName, 'regPassw' => $this->postRegPassw];
	}
}

$request = new Request();
$data = $request->requestHandler();

if(isset($_SERVER["HTTP_REFERER"])){
    $restprefix = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
    
    $rest = $restprefix . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    } else {
         $rest = "*";
    }
    
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Origin: ' . $rest . '');
	
echo json_encode($data, JSON_PRETTY_PRINT);