<?php
declare(strict_types=1);

class FolderGetNotes
{
    public function handle(): string
    {
        return $this->HandleNotes();
    }
    private function HandleNotes() : string
    {
        $Id = (int)trim($_POST['Id']);
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }

        return Database::Instance()->GetChildNotes($Id);
    }
}