<?php
declare(strict_types=1);

class Authorization
{
    public static function IsAuthenticated(): bool
    {
        return isset($_SESSION['AccountId']);
    }
    public static function LastLogin(): DateTime
    {
        return DateTime::createFromFormat('U', (string)($_SESSION['loginTime'] ?? ''));
    }
    public static function GetUser() : ?Account
    {
        if(self::IsAuthenticated())
        {
            return Database::instance()->GetAccountId((int)$_SESSION['AccountId']);
        }
        return null;
    }
    public static function Authenticate(int $id)
    {
        $_SESSION['AccountId'] = $id;
        $_SESSION['loginTime'] = time();
    }
    public static function Logout()
    {
        if(session_status() === PHP_SESSION_ACTIVE)
        {
            session_regenerate_id(true);
            session_destroy();
        }
    }
}