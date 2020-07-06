<?php
declare(strict_types=1);
class DoctorSubmit
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleDoctorForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleDoctorForm():?string
    {
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
            $Account = Database::Instance()->GetAccountUId($Id);

            if ($Account->PasswordMatches($Password))
            {
                $Statement = Database::Instance()->AddDoctor($Id, $Name, $Practice,
                    $Type, $Address, $Email, $Phone);
                if(!$Statement->rowCount())
                {
                    return $Statement->errorInfo()[2];
                }
                else
                {
                    return 'Success';
                }
            }
            return 'Password error';
        }
    }
}