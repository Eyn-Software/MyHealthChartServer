<?php
declare(strict_types=1);

class FolderSubmit
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleFolderForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleFolderForm():?string
    {
        $Password = trim($_POST['Password']);
        $UId = (int)trim($_POST['UId']);
        $Date = trim($_POST['CreationDate']);
        $Name = trim($_POST['Name'] ?? '');
        $PId = (int)trim($_POST['ParentFolderId'] ?? '');
        if(!$UId)
        {
            return 'Null User error';
        }
        else if(!$Name)
        {
            return 'Null name error';
        }
        else if(!$PId) {
            return 'Null parent error';
        }
        else
        {
            $Account = Database::Instance()->GetAccountUId($UId);
            if ($Account->PasswordMatches($Password)) {
                return Database::Instance()->AddFolder($UId, $Name, $Date, $PId);
            }
            return 'Password error';
        }
    }
}