<?php
declare(strict_types=1);

class DoctorList
{
    public function handle():? string
    {
        return $this->HandleList();
    }
    private function HandleList():?string
    {
        $Id = (int)trim($_POST['Id']);
        $Password = trim($_POST['Password'] ?? '');
        $Account = Database::Instance()->GetAccountUId($Id);
        if(!$Account->PasswordMatches($Password))
        {
            return 'Invalid login credentials';
        }
        return Database::Instance()->GetDoctors($Id);
    }
}