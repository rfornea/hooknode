<?php

require_once("IriData.php");

class NodeMessenger
{

    private $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
    );
    public $nodeUrl;
    private $userAgent = 'Codular Sample cURL Request';
    private $apiVersionHeaderString = 'X-IOTA-API-Version: ';

    public function __construct()
    {

        array_push($this->headers, $this->apiVersionHeaderString . $GLOBALS['apiVersion']);
    }

    private function validateUrl($nodeUrl)
    {

        $http = "((http)\:\/\/)"; // starts with http://
        $port = "(\:[0-9]{2,5})"; // ends with a port

        if (preg_match("/^$http/", $nodeUrl) && preg_match("/$port$/", $nodeUrl)) {
            return true;
        } else {
            throw new Exception('Invalid URL.');
        }
    }

    public function sendMessageToNode($nodeUrl, $commandObject)
    {

        try {
            $this->validateUrl($nodeUrl);
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . $GLOBALS['nl'];
        }

        $payload = http_build_query($commandObject);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            //CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_URL => $nodeUrl,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 1000
        ));

        echo $GLOBALS['nl'];
        echo $GLOBALS['nl'] . "calling curl in nodeMessenger, url is: " . $nodeUrl . $GLOBALS['nl'];
        echo $GLOBALS['nl'] . "payload: " . $payload . $GLOBALS['nl'];

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        echo $GLOBALS['nl'] . "response was: " . $GLOBALS['nl'];
        var_dump($response);

        return $response;
    }

    public function sendMessageToClient($commandObject, $url)
    {

    }
}


