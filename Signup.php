<?php
declare(strict_types=1);

class Signup
{
    public function handle():string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return $this->HandleSignup();
        }
        else
        {
            return "Request Type Error";
        }
    }
    //Takes all of the information from the sign up page, and adds a new account for the user
    private function HandleSignup() :?string
    {
        $formName = trim($_POST['Name'] ?? '');
        $formBirthday = trim($_POST['Birthday'] ?? '');
        $formEmail = trim($_POST['Email'] ?? '');
        $formPassword = trim($_POST['Password'] ?? '');

        if(!$formBirthday)
        {
            return 'Birthday null error';
        }
        else
        {
            try
            {
                $formBirthday= date('Y-m-d H:i:s', strtotime($formBirthday));
                $formBirthday = new DateTime($formBirthday);
                $Now = new DateTime();
                if($formBirthday > $Now)
                {
                    return "Birth not happened error";
                }
            }
            catch(exception $e)
            {
                return 'Birthday format error';
            }
        }
        if(!$formName)
        {
            return 'Name null error';
        }
        else if(!$formEmail)
        {
            return 'Email null error';
        }
        else if (!filter_var($formEmail, FILTER_VALIDATE_EMAIL))
        {
            return "Invalid email format";
        }
        else if(!$formPassword || strlen($formPassword) < 6)
        {
            return 'Password invalid error';
        }
        else
        {
            $Statement = Database::instance() -> AddAccount(strtolower($formEmail), $formPassword);
            if(!$Statement->rowCount())
            {
                return null;
            }
            $Account = Database::Instance()->GetAccount(strtolower($formEmail));
            $AId = $Account->GetId();
            $Statement = Database::instance() -> AddUser($formName, $formBirthday, $AId);
            if(!$Statement->rowCount())
            {
                return null;
            }
            return Database::Instance()->GetUsers($Account);
        }
    }
}
