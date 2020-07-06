<?php
declare(strict_types=1);

class User
{
    private $Id;
    private $Name;
    private $Birthday;
    private $AId;

    function __construct(array $input)
    {
        $this->Id = (int)($input['Id']);
        $this->Name = (string)($input['Name'] ?? '');
        $date = strtotime((string)($input['Birthday']));
        $this->Birthday = date('Y-m-d', $date);
        $this->AId = (int)($input['AId']);
    }
    public function GetId(): int
    {
        return $this->Id;
    }
    public function GetName(): string
    {
        return $this -> Name;
    }
    public function GetBirthday(): string
    {
        return $this->Birthday;
    }
    public function GetAId(): int
    {
        return $this->AId;
    }
}