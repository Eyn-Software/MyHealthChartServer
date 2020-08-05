<?php
declare(strict_types=1);

class NoteSubmit
{
    public function handle():?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleNoteForm();
        }
        else
        {
            return "Request Type Error";
        }
    }
    private function HandleNoteForm():?string
    {
        $Password = trim($_POST['Password']);
        $UId = (int)trim($_POST['UId']);
        $Date = trim($_POST['CreationDate']);
        $Name = trim($_POST['Name'] ?? '');
        $Description = trim($_POST['Description'] ?? '');
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
                return Database::Instance()->AddNote($UId, $Name, $Description, $Date, $PId);
            }
            return 'Password error';
        }
    }
}