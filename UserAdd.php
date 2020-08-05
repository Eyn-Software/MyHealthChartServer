<?php
declare(strict_types=1);

class UserAdd
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleUserForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleUserForm():?string
    {
        $Password = trim($_POST['Password'] ?? '');
        $UId = (int)trim($_POST['UId']);
        $Name = trim($_POST['Name'] ?? '');
        $Birthday = trim($_POST['Birthday'] ?? '');

        if(!$Name)
        {
            return 'Null name error';
        }
        else if(!$Birthday)
        {
            return 'Null birthday error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if ($Account->PasswordMatches($Password))
            {
                return Database::Instance()->AddUserWGet($Name, $Birthday, $Account->GetId());
            }
            return 'Password error';
        }
    }
}