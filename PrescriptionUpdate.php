<?php
declare(strict_types=1);

class PrescriptionUpdate
{
    public function handle(): string
    {
        return $this->HandleUpdate();
    }

    private function HandleUpdate() : string
    {
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $PId = (int)trim($_POST['PId']);
        $Name = trim($_POST['Name']);
        $StartDate = trim($_POST['StartDate']);
        $EndDate = trim($_POST['EndDate']);
        $ReminderTime = trim($_POST['ReminderTime'] ?? '');

        if (!$StartDate || !$EndDate) {
            return 'null date error';
        } else if (!$Name) {
            return 'null name error';
        } else {
            $Account = Database::Instance()->GetAccountUId($UId);
            if (!$Account->PasswordMatches($Password)) {
                return "";
            }
            return Database::Instance()->UpdatePrescription($PId, $Name, $StartDate, $EndDate, $ReminderTime);
        }
    }
}