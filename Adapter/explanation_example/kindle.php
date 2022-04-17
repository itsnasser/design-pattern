<?php


// exits class from installed package or some thing else
interface eReaderInterface
{
    public function turnOn();
    public function pressNextButton();
}


class Kindle implements eReaderInterface
{

    public function turnOn()
    {
        var_dump('turn the kindle on');
    }

    public function pressNextButton()
    {
        var_dump('press the next button on the kindle');
    }
}
