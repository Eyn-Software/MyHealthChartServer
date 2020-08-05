<?php
declare(strict_types=1);

class AppointmentUpdate
{
    public function handle(): string
    {
        return $this->HandleUpdate();
    }

    private function HandleUpdate() : string
    {
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Id = (int)trim($_POST['AId']);
        $Date = trim($_POST['Date']);
        $Reason = trim($_POST['Reason']);
        $Diagnosis = trim($_POST['Diagnosis'] ?? '');
        $Aftercare = trim($_POST['Aftercare'] ?? '');

        if(!$Date)
        {
            return 'null date error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if(!$Account->PasswordMatches($Password))
            {
                return "";
            }
            return Database::Instance()->UpdateAppointment($Id, $Date, $Reason, $Diagnosis, $Aftercare);
        }
    }
}