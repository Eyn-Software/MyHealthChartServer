<?php
declare(strict_types=1);

class Login
{
    public function handle():? string
    {
        return $this->HandleLogin();
    }
    private function HandleLogin():?string
    {
        $Email = trim($_POST['Email'] ?? '');
        $Password = trim($_POST['Password'] ?? '');
        $Account = Database::Instance()->GetAccount($Email);
        if(!$Account)
        {
            return null;
        }
        else if(!$Account->PasswordMatches($Password))
        {
            return null;
        }
        else
        {
            return Database::Instance()->GetUsers($Account);
        }
    }
}