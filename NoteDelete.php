<?php
declare(strict_types=1);

class NoteDelete
{
    public function handle(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->HandleNoteDelete();
        } else {
            return "Request Type Error";
        }
    }
    private function HandleNoteDelete(): ?string
    {
        $Password = trim($_POST['Password']);
        $UId = (int)trim($_POST['UId']);
        $Id = (int)trim($_POST['Id'] ?? '');

        $Account = Database::Instance()->GetAccountUId($UId);

        if ($Account->PasswordMatches($Password)) {
            return Database::Instance()->DeleteNote($Id);
        }
        return 'x';
    }
}