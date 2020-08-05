<?php
declare(strict_types=1);

class NoteGet
{
    public function handle(): string
    {
        return $this->HandleNote();
    }
    private function HandleNote() : string
    {
        $Id = (int)trim($_POST['Id']);
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }

        return Database::Instance()->GetNote($Id);
    }
}