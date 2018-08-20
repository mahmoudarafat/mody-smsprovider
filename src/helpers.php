<?php 

function lang($key, $replace=[],$locale=null){
	$locale = app()->getLocale();
	
    return app('translator')->trans($key, $replace, $locale);
}