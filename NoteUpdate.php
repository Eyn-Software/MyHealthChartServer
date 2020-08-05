<?php
declare(strict_types=1);

class NoteUpdate
{
    public function handle(): string
    {
        return $this->HandleNote();
    }
    private function HandleNote() : string
    {
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password'] ?? '');
        $Id = (int)trim($_POST['Id']);
        $Name = trim($_POST['Name'] ?? '');
        $Description = trim($_POST['Description'] ?? '');
        if(!$Name)
        {
            return 'Null name error';
        }
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }
        return Database::Instance()->UpdateNote($Id, $Name, $Description);
    }
}