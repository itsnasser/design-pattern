<?php

/**
 * The Component interface declares a filtering method that must be implemented
 * by all concrete components and decorators.
 */
interface CarService
{
    public function getCost(): int;
    public function getDescription(): string;
}


// core element of decoration
class BasicInspection implements CarService
{

    public function getCost(): int
    {
        return 10;
    }

    public function getDescription(): string
    {
        return 'Basic Inspection ';
    }
}


class TireRotation implements CarService
{

    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }


    public function getCost(): int
    {
        return 9 + $this->carService->getCost();
    }

    public function getDescription(): string
    {
        return $this->carService->getDescription() . ', and tire rotation ';
    }
}


class OilChange implements CarService
{

    protected $carService;
    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }


    public function getCost(): int
    {
        return 35 + $this->carService->getCost();
    }

    public function getDescription(): string
    {
        return $this->carService->getDescription() . ', and oil change ';
    }
}

$basicInspection = new BasicInspection;
$tireRotation  = new TireRotation($basicInspection);
$oilChange  = new OilChange($tireRotation);
$service = $oilChange;
echo $service->getDescription() . ' -- The Cost is :  ' . $service->getCost();
