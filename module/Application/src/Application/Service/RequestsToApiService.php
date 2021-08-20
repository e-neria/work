<?php

namespace Application\Service;

use Zend\Http\Client;
use Zend\Http\Request;

class RequestsToApiService
{
    public function requestApi($requestParameters)
    {
        $client = new Client();
        $request = new Request();

        $request->setUri($requestParameters['url']);
        $request->setMethod($requestParameters['method']);
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => "application/json",
        ));

        if(in_array($requestParameters['method'], array('POST', 'PUT', 'PATCH'))){
            $request->setContent(json_encode($requestParameters['payload']));
        }

        $client->setOptions($requestParameters['options']);

        try{
            $responseRequest = $client->dispatch($request);
            $response['body'] = json_decode($responseRequest->getBody(), true);
            $response['code'] = $responseRequest->getStatusCode();
        }catch (\Exception $exception){
            $response['body'] = 'Error al intentar conectar con: ' . $requestParameters['url'] . " mensaje: " . $exception->getMessage();
            $response['code'] = 500;
        }

        return $response;
    }
}