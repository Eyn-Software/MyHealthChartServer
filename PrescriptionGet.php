<?php
declare(strict_types=1);

class PrescriptionGet
{
    public function handle(): string
    {
        return $this->HandlePrescription();
    }
    private function HandlePrescription() : string
    {
        $PId = (int)trim($_POST['PId']);
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }
        return Database::Instance()->GetPrescription($PId);
    }
}