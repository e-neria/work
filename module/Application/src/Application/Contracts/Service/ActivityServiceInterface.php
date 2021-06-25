<?php

namespace Application\Contracts\Service;


interface ActivityServiceInterface
{
    public function getIndicators();
    public function getGridActivities($queryStrings);
    public function getListActivities();
    public function show($id);
}