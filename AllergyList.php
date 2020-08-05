<?php
declare(strict_types=1);

class AllergyList
{
    public function handle(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->HandleAllergyList();
        } else {
            return "Request Type Error";
        }
    }
    private function HandleAllergyList(): ?string
    {
        $Password = trim($_POST['Password']);
        $Id = (int)trim($_POST['UId']);

        $Account = Database::Instance()->GetAccountUId($Id);

        if ($Account->PasswordMatches($Password))
        {
            return Database::Instance()->GetAllergies($Id);
        }
    }
}