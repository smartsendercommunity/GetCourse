<?php

// v1   10.11.2021
// Powered by M-Soft
// https://t.me/mufik

ini_set('max_execution_time', '1700');
set_time_limit(1700);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: application/json; charset=utf-8');

http_response_code(200);

// Авторизационные данные

$token = "-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-";  // Здесь, внутри кавычек, вместо этих красивых полосок необходимо указать токен Вашего проекта Smart Sender

// functions
function send_forward($inputJSON, $link){
	
$request = 'POST';	
		
$descriptor = curl_init($link);

 curl_setopt($descriptor, CURLOPT_POSTFIELDS, $inputJSON);
 curl_setopt($descriptor, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($descriptor, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 curl_setopt($descriptor, CURLOPT_CUSTOMREQUEST, $request);

    $itog = curl_exec($descriptor);
    curl_close($descriptor);

   		 return $itog;
		
}
function send_bearer($url, $token, $type = "GET", $param = []){
	
		
$descriptor = curl_init($url);

 curl_setopt($descriptor, CURLOPT_POSTFIELDS, json_encode($param));
 curl_setopt($descriptor, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($descriptor, CURLOPT_HTTPHEADER, array('User-Agent: M-Soft Integration', 'Content-Type: application/json', 'Authorization: Bearer '.$token)); 
 curl_setopt($descriptor, CURLOPT_CUSTOMREQUEST, $type);

    $itog = curl_exec($descriptor);
    curl_close($descriptor);

   		 return $itog;
		
}

$link = "https://webhook.site/getcourse";
$result["time"] = time();
$result["date"] = date("Y-m-d H:i:s");
$result["get"] = $_GET;
$result["url"] = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
// Проверка типа запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	$result["state"] = false;
	$result["message"] = "Method not Allowed. Please, use POST";
	echo json_encode($result);
	send_forward(json_encode($result), $link."?methot=notAllowed");
	exit;
}

// Проверка наличия всех обезательных полей
if ($_GET["ssId"] == NULL) {
    $result["state"] = false;
    $result["message"]["account"] = "ssId is missing";
    http_response_code(422);
    echo json_encode($result);
    send_forward(json_encode($result), $link);
    exit;
}

// Подготовка данных и отправка в Smart Sender
if (is_array($_GET["addTags"]) === true) {
    foreach ($_GET["addTags"] as $addTags) {
        $tagsData = json_decode(send_bearer("https://api.smartsender.com/v1/tags?page=1&limitation=20&term=".$addTags, $token), true);
        if (is_array($tagsData["collection"]) === true) {
            foreach ($tagsData["collection"] as $tagsSS) {
                if ($tagsSS["name"] == $addTags) {
                    $result["addTags"][] = json_decode(send_bearer("https://api.smartsender.com/v1/contacts/".$_GET["ssId"]."/tags/".$tagsSS["id"], $token, "POST"), true);
                    break;
                }
            }
        }
    }
}
if (is_array($_GET["delTags"]) === true) {
    foreach ($_GET["delTags"] as $delTags) {
        $tagsData = json_decode(send_bearer("https://api.smartsender.com/v1/tags?page=1&limitation=20&term=".$delTags, $token), true);
        if (is_array($tagsData["collection"]) === true) {
            foreach ($tagsData["collection"] as $tagsSS) {
                if ($tagsSS["name"] == $delTags) {
                    $result["delTags"][] = json_decode(send_bearer("https://api.smartsender.com/v1/contacts/".$_GET["ssId"]."/tags/".$tagsSS["id"], $token, "DELETE"), true);
                    break;
                }
            }
        }
    }
}
if (is_array($_GET["addFunnels"]) === true) {
    foreach ($_GET["addFunnels"] as $addFunnels) {
        $funnelsData = json_decode(send_bearer("https://api.smartsender.com/v1/funnels?page=1&limitation=20&term=".$addFunnels, $token), true);
        if (is_array($funnelsData["collection"]) === true) {
            foreach ($funnelsData["collection"] as $funnelsSS) {
                if ($funnelsSS["name"] == $addFunnels) {
                    $result["addFunnels"][] = json_decode(send_bearer("https://api.smartsender.com/v1/contacts/".$_GET["ssId"]."/funnels/".$funnelsSS["serviceKey"], $token, "POST"), true);
                    break;
                }
            }
        }
    }
}
if (is_array($_GET["delFunnels"]) === true) {
    foreach ($_GET["delFunnels"] as $delFunnels) {
        $funnelsData = json_decode(send_bearer("https://api.smartsender.com/v1/funnels?page=1&limitation=20&term=".$delFunnels, $token), true);
        if (is_array($funnelsData["collection"]) === true) {
            foreach ($funnelsData["collection"] as $funnelsSS) {
                if ($funnelsSS["name"] == $delFunnels) {
                    $result["delFunnels"][] = json_decode(send_bearer("https://api.smartsender.com/v1/contacts/".$_GET["ssId"]."/funnels/".$funnelsSS["serviceKey"], $token, "DELETE"), true);
                    break;
                }
            }
        }
    }
}
if (is_array($_GET["triggers"]) === true) {
    foreach ($_GET["triggers"] as $triggers) {
        $result["triggers"][] = json_decode(send_bearer("https://api.smartsender.com/v1/contacts/".$_GET["ssId"]."/fire?name=".$triggers, $token, "POST"), true);
    }
}
if (is_array($_GET["variables"]) === true) {
    foreach ($_GET["variables"] as $varKey => $varValue) {
        $sendVar["values"][$varKey] = $varValue;
    }
    $updateUser = json_decode(send_bearer("https://api.smartsender.com/v1/contacts/".$_GET["ssId"], $token, "PUT", $sendVar), true);
    $result["send"] = $sendVar;
    $result["update"] = $updateUser;
}
send_forward(json_encode($result), $link);












