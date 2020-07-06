<?php
declare(strict_types=1);

class AppointmentSubmit
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleAppointmentForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleAppointmentForm():?string
    {
        $Password = trim($_POST['Password']);
        $UId = (int)trim($_POST['UId']);
        $DId = (int)trim($_POST['DId']);
        $Date = trim($_POST['Date']);
        $ReminderTime = trim($_POST['ReminderTime'] ?? '');
        $Reason = trim($_POST['Reason'] ?? '');
        $Diagnosis = trim($_POST['Diagnosis'] ?? '');
        $Aftercare = trim($_POST['Aftercare'] ?? '');
        if(!$UId)
        {
            return 'Null User error';
        }
        else if(!$DId)
        {
            return 'Null doctor error';
        }
        else if(!$Date) {
            return 'Null date error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if ($Account->PasswordMatches($Password)) {
                return Database::Instance()->AddAppointment($UId, $DId, $Date, $ReminderTime,
                    $Reason, $Diagnosis, $Aftercare);
            }
            return 'Password error';
        }
    }
}