<?php
declare(strict_types=1);

class VaccineSubmit
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleVaccineForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleVaccineForm():?string
    {
        $Name = trim($_POST['Name'] ?? '');
        $Date = trim($_POST['Date'] ?? '');
        $AId = (int)trim($_POST['AId']);
        $DId = (int)trim($_POST['DId']);
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);

        if(!$UId)
        {
            return 'Null User error';
        }
        else if(!$Name)
        {
            return 'Null name error';
        }
        else if(!$AId)
        {
            return 'Null appointment error';
        }
        else if(!$DId){
            return 'Nul doctor error';
        }
        else if(!$Date) {
            return 'Null date error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if ($Account->PasswordMatches($Password)) {
                return Database::Instance()->AddVaccine($Name, $Date, $AId, $DId, $UId);
            }
            return 'Password error';
        }
    }
}