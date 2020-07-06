<?php
declare(strict_types=1);

class PrescriptionSubmit
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandlePrescriptionForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandlePrescriptionForm():?string
    {
        $Name = trim($_POST['Name'] ?? '');
        $StartDate = trim($_POST['StartDate'] ?? '');
        $EndDate = trim($_POST['EndDate'] ?? '');
        $ReminderTime = trim($_POST['ReminderTime'] ?? '');
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
        else if(!$EndDate) {
            return 'Null date error';
        }
        else if(!$StartDate)
        {
            return 'Null date error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if ($Account->PasswordMatches($Password)) {
                return Database::Instance()->AddPrescription($Name, $StartDate, $EndDate,
                    $ReminderTime, $AId, $DId, $UId);
            }
            return 'Password error';
        }
    }
}