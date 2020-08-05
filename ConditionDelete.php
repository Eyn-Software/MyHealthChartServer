<?php
declare(strict_types=1);

class ConditionDelete
{
    public function handle(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->HandleConditionDelete();
        } else {
            return "Request Type Error";
        }
    }

    private function HandleConditionDelete(): ?string
    {
        $Password = trim($_POST['Password']);
        $Id = (int)trim($_POST['UId']);
        $Type = trim($_POST['Type'] ?? '');

        if(!$Type)
        {
            return "Null type error";
        }
        $Account = Database::Instance()->GetAccountUId($Id);

        if ($Account->PasswordMatches($Password))
        {
            return Database::Instance()->DeleteCondition($Id, $Type);
        }
    }
}