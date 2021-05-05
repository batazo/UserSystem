<?php 

class Test {
	
	public $valtozo;
	public $good;
	public $bad;
	
	function __construct(){
		$this->valtozo = (isset($_POST['num'])) ? $_POST['num'] : false;
		$this->good = 'GOOD';
		$this->bad = 'BAD';
	}
    
	public function handler(){
		if($this->valtozo == 4){
			return $this->rendben();
		} else {
			return $this->nincsRendben();
		}
	}
	
	public function rendben(){
		return Array( $this->good => $this->valtozo ); 
	}
	
	public function nincsRendben(){
		return $this->bad; 
	}	
}

$testclass = new Test();
$data = $testclass->handler();


if(isset($_SERVER["HTTP_REFERER"])){
    $restprefix = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
    
    $rest = $restprefix . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    } else {
         $rest = "*";
    }
    
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Origin: ' . $rest . '');
	
var_dump($data);