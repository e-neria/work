<?php

namespace Application\Service;

use Application\Contracts\Service\ObservationServiceInterface;

class ObservationService implements ObservationServiceInterface
{
    private $requestsToApiService;
    private $productivityApp;

    public function __construct(RequestsToApiService $apiService, $productivityApp)
    {
        $this->requestsToApiService = $apiService;
        $this->productivityApp = $productivityApp;
    }

    public function index($id)
    {
        $url = $this->productivityApp['url'] . "/observations?id=" . $id;
        $requestParameters = [
            'url' => $url,
            'method' => 'GET',
            'options' => $this->productivityApp['options']
        ];

        $data = $this->requestsToApiService->requestApi($requestParameters);

        if(!empty($data))
            $response = $data;
        else
            $response = [];

        return $response;
    }

    public function add($data)
    {
        $url = $this->productivityApp['url'] . "/observations";
        $payload['id_activity'] = (int) $data['activityId'];
        $payload['observation'] = $data['observation'];

        $requestParameters = [
            'url' => $url,
            'method' => "POST",
            'options' => $this->productivityApp['options'],
            'payload' => $payload
        ];

        return $this->requestsToApiService->requestApi($requestParameters);

    }
}