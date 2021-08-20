<?php

namespace Application\Contracts\Service;

interface ObservationServiceInterface
{
    public function index($id);
    public function add($data);
}