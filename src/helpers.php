<?php 

if (!function_exists('get_url')) {

     function get_url()
     {

		$u = url($url);

		if (strpos($u, 'public') !== false) {
			return $u;
		}else{
			$url = "public\\".$url;
			$u = url($url);
			return $u;
		}
	}

}

?>
