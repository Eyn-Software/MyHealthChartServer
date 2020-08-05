<?php
declare(strict_types=1);

class ConditionList
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleConditionList();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleConditionList() : string
    {
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }
        return Database::Instance()->GetConditions($UId);
    }
}