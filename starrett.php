<?php
// INSTALA: "guzzlehttp/guzzle": "~5.0" con composer
require ('vendor/autoload.php');

function copyRemote($fromUrl, $toFile) {
    try {
        $client2 = new GuzzleHttp\Client();
        $response2 = $client2->get($fromUrl, ['save_to' => $toFile]);
            //->setAuth('login', 'password') // in case your resource is under protection
            //->save_to($toFile)
            //->send();
        return true;
    } catch (Exception $e) {
        // Log the error or something
        return false;
    }
}

$raiz_fuente = 'http://www.starrett.com/metrology/product-detail/';
$miruta = 'catalogo/';
$list = array();
$list1 = array();
$edp = 'T469HXSP';

$client = new GuzzleHttp\Client();
$response = $client->get($raiz_fuente . $edp);
//var_dump((string)$response->getBody());

$regex = '/<div class=\"image\"(.*?)<\/div>/s';
$regex1= '/a href=\"(.*?)\"/';

if ( preg_match_all($regex, $response, $list) )
    {
    	if ( preg_match($regex1, $list[0][0], $list1) )
    		{ /*echo $i;*/ $a1=$list1[1];}
		else
    		{ print "Not found 1"; $a1="";}
    	//echo '<img src="' . $a1 . '">';
    	copyRemote($a1, $miruta . $edp . ".jpg");
    }
    else
    	print "Not found 0";



?>