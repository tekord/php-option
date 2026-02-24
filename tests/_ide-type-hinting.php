<?php

// This is a helper file to check if an IDE infers types correctly.
// Put the cursor at the end of a value getting function call and see if your IDE shows a hint

use Tekord\Option\Option;
use Tekord\Option\Tests\Classes\BookDto;
use Tekord\Option\Tests\Classes\PersonDto;

{
    $person = Option::some(new PersonDto());

    //                       |
    //                       V - put your cursor here
    $it = $person->getValue();
}

{
    $book = Option::some(new BookDto());

    //                   |
    //                   V - put your cursor here
    $it = $book->unwrap();
}

{
    $maybePerson = Option::none();

    //                                                  |
    //                                                  V - put your cursor here
    $it = $maybePerson->unwrapOrDefault(new PersonDto());
}

{
    $maybeBook = Option::none();

    //                                              |
    //                                              V - put your cursor here
    $it = $maybeBook->unwrapOrDefault(new BookDto());
}

{
    $anotherPerson = Option::some(new PersonDto());

    //                         |
    //                         V - put your cursor here
    $it = $anotherPerson->value;
}

{
    /** @return Option<PersonDto> */
    function getPerson() {
        return Option::none();
    }

    $it = getPerson();
}
