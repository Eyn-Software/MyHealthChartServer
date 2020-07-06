<?php
declare(strict_types=1);

class Doctor
{
    private $Id;
    private $Name;
    private $Practice;
    private $Type;
    private $Address;
    private $Email;
    private $Phone;

    function __construct(array $input)
    {
        $this->Id = (int)($input['Id']);
        $this->Name = (string)($input['Name']);
        $this->Practice = (string)($input['Practice']);
        $this->Type = (string)($input['Type']);
        $this->Address = (string)($input['Address']);
        $this->Email = (string)($input['Email']);
        $this->Phone = (string)($input['Phone']);
    }
}