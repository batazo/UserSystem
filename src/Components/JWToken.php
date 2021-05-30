<?php 

namespace UserSystem\Components;
use UserSystem\Component;

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

class JWToken extends Component
{
	private $serverTokenKey;
	private $issuedAt;
	
	function __construct()
		{
			$this->serverTokenKey = new Token();
			$this->issuedAt = new \DateTimeImmutable();
		}
	
	public function getServerToken(){
		$issuedAt   = $this->issuedAt->getTimestamp();
		$secretKey = $this->serverTokenKey->getServerToken();
		return $secretKey;
	}
	
	public function createToken($userName = '', $userId = '', $userSecret = '', $minute = '5'){
		$secretKey = $this->serverTokenKey->getServerToken();
		$issuedAt = $this->issuedAt;
		$expire = $this->issuedAt->modify("+" . $minute . " minutes")->getTimestamp();
		
		$data = [
			'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
			'iss'  => 'UserSystem',                       // Issuer
			'nbf'  => $issuedAt->getTimestamp(),         // Not before
			'exp'  => $expire,                           // Expire
			'userName' => $userName,                     // User name
			'userId' => $userId,
			'userSecret' => $userSecret,
		];
		
		return JWT::encode(
        $data,
        $secretKey,
        'HS512'
		);
	}
	
	public function decodeToken($jwt = '') {
		try{
		$secretKey = $this->serverTokenKey->getServerToken();
		$token = JWT::decode($jwt, $secretKey, ['HS512']);
		return $token;
	    }catch(\Exception $err){ return false;}
	}

	public function tokenExpireCheck($jwt = '') {
		$token = $this->decodeToken($jwt);
		$iss = 'UserSystem';
		$now = new \DateTimeImmutable();
		
		return ($token && $token->iss == $iss && $token->exp > $now->getTimestamp() ) ?  'true' : 'false';
	}
	
	public function getTokenUserName($jwt = ''){
		$token = $this->decodeToken($jwt);
		return ($token) ? $token->userName : 'false';
	}

	public function getTokenUserSecret($jwt = ''){
		$token = $this->decodeToken($jwt);
		return ($token) ? $token->userSecret : 'false';
	}

}
