<?php

namespace Application\Service;

use Application\Contracts\Service\ActivityServiceInterface;

class ActivityService implements ActivityServiceInterface
{
    private $requestsToApiService;
    private $productivityApp;

    public function __construct(RequestsToApiService $apiService, $productivityApp)
    {
        $this->requestsToApiService = $apiService;
        $this->productivityApp = $productivityApp;
    }

    public function getIndicators()
    {
        $url = $this->productivityApp['url'] . "/activities/getIndicatorsHome";
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

    public function getGridActivities($queryStrings)
    {
        $url = $this->productivityApp['url'] . "/activities";
        $limit = (!empty($queryStrings['limit'])) ? $queryStrings['limit'] : 20;

        foreach ($queryStrings as $keyQueryString => $queryString){
            switch ($keyQueryString){
                case 'search':
                    $url .= (!empty($queryString)) ? "?search=" . $queryString : "";
                    break;
                case 'sort':
                    $operator = (strpos($url, "?")) ? "&" : "?";
                    $url .= (!empty($queryString)) ? $operator . "sort=" . $queryString : "";
                    break;
                case 'order':
                    $operator = (strpos($url, "?")) ? "&" : "?";
                    $url .= (!empty($queryString)) ? $operator . "order=" . $queryString : "";
                    break;
                case 'offset':
                    $operator = (strpos($url, "?")) ? "&" : "?";
                    $page = ($queryString / $limit) + 1;
                    $url .= $operator . "page=" . $page;
                    break;
                case 'limit':
                    $operator = (strpos($url, "?")) ? "&" : "?";
                    $url .= (!empty($queryString)) ? $operator . "limit=" . $queryString : $operator . "limit=" . $limit;
                    break;
            }
        }

        $requestParameters = [
            'url' => $url,
            'method' => "GET",
            'options' => $this->productivityApp['options']
        ];
        $data = $this->requestsToApiService->requestApi($requestParameters);

        $response = ['rows' => [], 'total' => 0];
        if(!empty($data)){
            $response['rows'] = $data['body']['data']['data'];
            $response['total'] = $data['body']['data']['total'];
        }

        return $response;
    }

    public function getListActivities()
    {
        $url = $this->productivityApp['url'] . "/activities/getListActivities";
        $requestParameters = [
            'url' => $url,
            'method' => "GET",
            'options' => $this->productivityApp['options']
        ];
        return $this->requestsToApiService->requestApi($requestParameters);
    }

    public function show($id)
    {
        $url = $this->productivityApp['url'] . "/activities/" . $id;
        $requestParameters = [
            'url' => $url,
            'method' => "GET",
            'options' => $this->productivityApp['options']
        ];

        $data = $this->requestsToApiService->requestApi($requestParameters);

        $activity = [];
        if($data['code'] == 200){
            if(!empty($data['body']['data'])){
                $activity = $data['body']['data']['0'];
                //if(!empty($activity)){
                    $activity['datePickerStart'] = date('m/d/Y', strtotime($activity['datePickerStart']));
                    $activity['datePickerEnd'] = date('m/d/Y', strtotime($activity['datePickerEnd']));
                //}
            }
        }

        return $activity;
    }

    public function save($data)
    {
        $url = $this->productivityApp['url'] . "/activities";
        $method = "POST";

        if($data['formAction'] != "add"){
            $url = $this->productivityApp['url'] . "/activities/" . $data['id'];
            $method = "PUT";
        }

        $data['type'] = (int) $data['typeActivity'];
        $data['status'] = (int) $data['status'];
        $data['start'] = $data['datePickerStart'];
        $data['end'] = $data['datePickerEnd'];

        $requestParameters = [
            'url' => $url,
            'method' => $method,
            'options' => $this->productivityApp['options'],
            'payload' => $data
        ];

        return $this->requestsToApiService->requestApi($requestParameters);
    }


}