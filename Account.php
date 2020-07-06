<?php
declare(strict_types=1);
class Account
{
    private $Id;
    private $Email;
    private $Password;

    function __construct(array $input)
    {
        $this->Id = (int)($input['Id']);
        $this->Email = (string)($input['Email'] ?? '');
        $this->Password = (string)($input['Password'] ?? '');
    }
    public function GetId():?int
    {
        return $this->Id;
    }
    public function PasswordMatches(string $AttemptedPass)
    {
        return password_verify($AttemptedPass, $this -> Password);
    }
}