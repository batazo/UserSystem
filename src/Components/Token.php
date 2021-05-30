<?php
namespace UserSystem\Components;

use UserSystem\Component;
use UserSystem\Components\DataSource;

class Token extends Component
{
    private $ds;

    function __construct()
    {
        $this->ds = new DataSource();
    }
	
	function getServerToken(){

		$query = "select OPT_VALUE FROM Options WHERE OPT_KEY LIKE 'JWT_S_KEY'";
		$ServerTokenResult = $this->ds->select($query);

        return $ServerTokenResult[0]['OPT_VALUE'];
	}
	
}