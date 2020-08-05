<?php
declare(strict_types=1);

class FolderGetRoot
{
    public function handle(): string
    {
        return $this->HandleFolder();
    }
    private function HandleFolder() : string
    {
        $UId = (int)trim($_POST['UId']);
        $Password = trim($_POST['Password']);
        $Account = Database::Instance()->GetAccountUId($UId);
        if(!$Account->PasswordMatches($Password))
        {
            return "";
        }

        return Database::Instance()->GetRootFolder($UId);
    }
}