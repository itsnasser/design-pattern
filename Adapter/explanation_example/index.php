<?php


require 'book.php';
require 'eReaderAdapter.php';
//  The client code supports all classes
class Person
{

    function read(BookInterface $book)
    {
        $book->open();
        $book->turnPage();
    }
}


(new Person)->read(new eReaderAdapter(new Kindle));
(new Person)->read(new Book);
