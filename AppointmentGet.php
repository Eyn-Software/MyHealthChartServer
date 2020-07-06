<?php
declare(strict_types=1);

class AppointmentGet
{
    public function handle(): string
    {
        return $this->HandleAppointment();
    }
    private function HandleAppointment() : string
    {
        $AId = (int)trim($_POST['AId']);
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }
        return Database::Instance()->GetAppointment($AId);
    }
}