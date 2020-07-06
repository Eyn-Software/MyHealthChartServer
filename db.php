<?php
declare(strict_types=1);
    $pdo;
    $dsn = "mysql:host=127.0.0.1;dbname=MyHealthChart;port=3306;charset=utf8mb4";
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

    //Constructs the Database object, which is used to access the database.
    private function __construct()
    {
        $dsn = "mysql:host=127.0.0.1;dbname=MyHealthChart;port=3306;charset=utf8mb4";
        $options = [

            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        ];
        $this->pdo = new PDO($dsn, "guest", "", $options);
    }
    //Creates an instance of the database if there isn't one already
    /*
    public static function Instance()
    {
        static $instance;
        if (is_null($instance))
        {
            ob_start();
            echo 'd';
            $instance = new static();
            echo ' ';
            ob_end_flush();
        }
        echo 'e';
        return $instance;
    }
    */
    //Adds an account to the database
    public function AddAccount(string $Email, string $Password)
    {
        $Statement = $this->pdo->prepare("INSERT INTO Account(Email, Password)
                                        values (:Email, :Password)");
        $Statement->execute([
            ':Email' => $Email,
            ':Password' => password_hash($Password, PASSWORD_BCRYPT)
        ]);
        return $Statement;
    }
    //Adds a user to the database
    public function AddUser(string $Name, DateTime $Birthday, int $AId)
    {
        $Birthday = $Birthday->format('Y-m-d H:i:s');
        $Statement = $this->pdo->prepare("insert into User(Name, Birthday, AId) 
            values (:Name, :Birthday, :AId)");
        $Statement->execute([
            ':Name' => $Name,
            ':Birthday' => $Birthday,
            ':AId' => $AId
        ]);
        return $Statement;
    }
    //Adds a doctor to the database
    public function AddDoctor(int $UId, string $Name, string $Practice, string $Type,
                              string $Address, string $Email, string $Phone)
    {
        $Statement = $this->pdo->prepare("insert into Doctor(Name, Practice, Type, Address, 
                   Email, Phone) values (:Name, :Practice, :Type, :Address, :Email, :Phone)");
        $Statement->execute([
            ':Name' => $Name,
            ':Practice' => $Practice,
            ':Type' => $Type,
            ':Address' => $Address,
            ':Email' => $Email,
            ':Phone' => $Phone
        ]);
        $Id = $this->pdo->lastInsertId();
        if (!$Statement->rowCount())
        {
            return $Statement;
        }
        else
        {
            $Statement = $this->pdo->prepare("insert into User_Doctor_Junction(UId, DId)
                    values (:UId, :DId)");
            $Statement->execute([
                ':UId' => $UId,
                ':DId' => $Id
            ]);
            return $Statement;
        }
    }
    //Gets account by Id
    public function GetAccountId(int $Id):? Account
    {
        $Statement = $this->pdo->prepare("Select * from Account where Id= :Id");
        if($Statement->execute([':Id' => $Id] && ($data = $Statement->fetch(PDO::FETCH_ASSOC))))
        {
            return new Account($data);
        }
        return null;
    }
    //Gets an account by email address
    public function GetAccount(string $Email):? Account
    {
        echo 'b';
        $Statement = $this->pdo->prepare("Select * from Account where Email = :Email");
        if ($Statement->execute([':Email' => $Email]) && ($data = $Statement->fetch(PDO::FETCH_ASSOC)))
        {
            return new Account($data);
        }
        return null;
    }
    //Gets an account by the user's Id
    public function GetAccountUId(int $UId):? Account
    {
        $User = $this->GetUser($UId);
        $Statement = $this->pdo->prepare("select * from Account where Id = :Id");
        if($Statement->execute([':Id' => $User->GetAId()]) && ($data = $Statement->fetch(PDO::FETCH_ASSOC)))
        {
            return new Account($data);
        }
        return null;
    }
    //Gets user by Id
    public function GetUser(int $Id):? User
    {
        $Statement = $this->pdo->prepare("select * from User where Id = :Id");
        if($Statement->execute([':Id' => $Id]) && ($data = $Statement->fetch(PDO::FETCH_ASSOC)))
        {
            return new User($data);
        }
        return null;
    }
}