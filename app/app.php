<?php

use Phalcon\Http\Response;

function binarySearch(Array $arr, $x)
{
    // check for empty array
    if (count($arr) === 0) return false;
    $low = 0;
    $high = count($arr) - 1;

    while ($low <= $high) {

        // compute middle index
        $mid = floor(($low + $high) / 2);

        // element found at mid
        if($arr[$mid] == $x) {
            return true;
        }

        if ($x < $arr[$mid]) {
            // search the left side of the array
            $high = $mid -1;
        }
        else {
            // search the right side of the array
            $low = $mid + 1;
        }
    }

    // If we reach here element x doesnt exist
    return false;
}

/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get('/', function () {
	$response = new Response();
	$response->setJsonContent(['message' => 'hello world']);
	return $response;
});

$app->get('/compute', function() {
	$x = 0; $y = 1;
        $max = 10000 + rand(0, 500);

        for ($i = 0; $i <= $max; $i++) {
            $z = $x + $y;
            $x = $y;
            $y = $z;
	}

	$response = new Response();
        $response->setJsonContent(['message' => 'done']);
        return $response;
});

$app->get('/countries', function() {
	$db = \Phalcon\Di::getDefault()->get('db');
	$statement = $db->prepare("Select * from apps_countries");
	$statement->execute();
	$result = $statement->fetchAll();
	
	$response = new Response();
        $response->setJsonContent(['data' => $result]);
        return $response;
});

$app->get('/country', function() {
        $db = \Phalcon\Di::getDefault()->get('db');
        $statement = $db->prepare("Select * from apps_countries where country_code='PT'");
        $statement->execute();
        $result = $statement->fetch();

        $response = new Response();
        $response->setJsonContent(['data' => $result]);
        return $response;
});
	
$app->get('/search', function() {
        $array = [];
	for($i = 0; $i < 10000; $i++) {
		$array[] = $i;
	}
	$number = rand(1, 10000);	
	$result = binarySearch($array, $number);
        $response = new Response();
        $response->setJsonContent(['status' => 'done', 'numberSearched' => $number]);
        return $response;
});
/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
