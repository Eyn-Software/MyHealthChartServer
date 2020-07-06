<?php
declare(strict_types=1);

class UpdateDoctor
{
    public function handle(): string
    {
        return $this->HandleDoctor();
    }
    private function HandleDoctor() : string
    {
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Id = (int)trim($_POST['Id']);
        $Name = trim($_POST['Name'] ?? '');
        $Practice = trim($_POST['Practice'] ?? '');
        $Type = trim($_POST['Type'] ?? '');
        $Address = trim($_POST['Address'] ?? '');
        $Phone = trim($_POST['Phone'] ?? '');
        $Email = trim($_POST['Email'] ?? '');

        if(!$Name)
        {
            return 'Null name error';
        }
        else if(!$Practice)
        {
            return 'Null practice error';
        }
        else if (strlen($Phone) > 15)
        {
            return 'Bad phone error';
        }
        else if (!$Id)
        {
            return 'Null user ID';
        }
        else if (!$Password)
        {
            return 'Null password error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if(!$Account->PasswordMatches($Password))
            {
                return "";
            }
            return Database::Instance()->UpdateDoctor($Id, $Name, $Practice, $Type,
                                                    $Address, $Email, $Phone);
        }
    }
}