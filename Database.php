<?php
declare(strict_types=1);
class Database
{
    public $pdo;
    //Constructs the Database object, which is used to access the database.
    private function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=MyHealthChart;port=3306;charset=utf8mb4";
        $options = [

            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        ];
        $this->pdo = new PDO($dsn, "guest", "", $options);
    }
    //Creates an instance of the database if there isn't one already
    public static function Instance()
    {
        static $instance;
        if (is_null($instance))
        {
            $instance = new static();
        }
        return $instance;
    }

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
    public function AddAppointment(int $UId, int $DId, string $Date, string $ReminderTime,
                                    string $Reason, string $Diagnosis, string $Aftercare)
    {

        $Statement = $this->pdo->prepare("insert into Appointment(Date, ReminderTime, 
                        Aftercare, Reason, Diagnosis, DId, UId) values (:Date, :ReminderTime,
                                                                :Aftercare, :Reason, :Diagnosis, :DId, :UId)");
        $Statement->execute([
            ':Date' => $Date,
            ':ReminderTime' => $ReminderTime,
            ':Aftercare' => $Aftercare,
            ':Reason' => $Reason,
            ':Diagnosis' => $Diagnosis,
            ':DId' => $DId,
            ':UId' => $UId
        ]);
        if (!$Statement->rowCount())
        {
            return $Statement->errorInfo()[2];
        }
        else
        {
            return $this->pdo->lastInsertId();
        }
    }
    public function AddPrescription(string $Name, string $StartDate, string $EndDate, string $ReminderTime,
                                    int $AId, int $DId, int $UId)
    {
        $Statement = $this->pdo->prepare("insert into Prescription(Name, StartDate, 
                         EndDate, ReminderTime, AId, DId, UId) values(
                        :Name, :StartDate, :EndDate, :ReminderTime, :AId, :DId, :UId)");
        $Statement->execute([
            ':Name' => $Name,
            ':StartDate' => $StartDate,
            ':EndDate' => $EndDate,
            ':ReminderTime' => $ReminderTime,
            ':AId' => $AId,
            ':DId' => $DId,
            ':UId' => $UId
        ]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorInfo();
        }
        else
        {
            return 'Success';
        }
    }
    public function AddVaccine(string $Name, string $Date, int $AId, int $DId, int $UId)
    {
        $Statement = $this->pdo->prepare("insert into Vaccine(Name, Date,
                    AId, DId, UId) values( :Name, :Date, :AId, :DId, :UId)");
        $Statement->execute([
            ':Name' => $Name,
            ':Date' => $Date,
            ':AId' => $AId,
            ':DId' => $DId,
            ':UId' => $UId
        ]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorInfo();
        }
        else
        {
            return 'Success';
        }
    }
    public function UpdateDoctor(int $Id, string $Name, string $Practice, string $Type,
                            string $Address, string $Email, string $Phone)
    {
        $Statement = $this->pdo->prepare("update Doctor
                                                    set Name = :Name, Practice = :Practice,
                                                    Type = :Type, Address = :Address, Email = :Email, Phone = :Phone
                                                    where Id = :Id");
        $Statement->execute([
            ':Name' => $Name,
            ':Practice' =>$Practice,
            ':Type' => $Type,
            ':Address' => $Address,
            ':Email' => $Email,
            ':Phone' => $Phone,
            ':Id' => (int)$Id
        ]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorInfo();
        }
        return 'Success';
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
    //Make sure that the user sees the doctor then return the doctor
    public function GetDoctor(int $Id, int $UId):? string
    {
        $ReturnString = "";
        $offset = 0;
        $Statement = $this->pdo->prepare("select * from User_Doctor_Junction
                                                    where UId = :UId");
        if($Statement->execute([':UId' => $UId]))
        {
            foreach($Statement as $row)
            {
                $ReturnString = implode("///", $row);
                $offset = strpos($ReturnString, "///");
                if($offset !== false)
                {
                    break;
                }
            }
            if($offset !== false)
            {
                $Statement = $this->pdo->prepare("select * from Doctor
                                                    where Id = :Id");
                if($Statement->execute([':Id' => $Id])&& ($data = $Statement->fetch(PDO::FETCH_ASSOC)));
                {
                    return implode("///", $data);
                }
            }
        }
        return null;
    }
    //Select Doctor with appointment Id
    public function GetDoctorAId(int $AId):? string
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Doctor.Name, Doctor.Address
                                                    from Doctor 
                                                    join Appointment on Doctor.Id = Appointment.DId
                                                    where Appointment.Id = :AId");
        if ($Statement->execute([':AId' => $AId]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
    }

    //Get users on account
    public function GetUsers(Account $Account)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select * from User where AId = :AId");
        if($Statement->execute([':AId' => $Account->GetId()]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        else
        {
            return null;
        }
    }

    //Gets all doctors with user
    public function GetDoctors(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Doctor.* from Doctor
                                                    join User_Doctor_Junction on Doctor.Id = User_Doctor_Junction.DId
                                                    where User_Doctor_Junction.UId = :UId");
        if($Statement->execute([':UId' => $id]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }

            return $ReturnString;
        }
        else
        {
            return null;
        }
    }

    //Gets all appointments with user
    public function GetAppointments(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Appointment.Id, Doctor.Name, Appointment.Date 
                                                    from Appointment
                                                    join Doctor on Appointment.DId = Doctor.Id
                                                    where Appointment.UId = :UId");
        if($Statement->execute([':UId' => $id]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///",$row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        else
        {
            return null;
        }
    }
    public function GetAppointment(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Id, Date, ReminderTime, Aftercare, 
                                                    Reason, Diagnosis from Appointment where Id = :AId");

        if($Statement->execute([':AId' => $id])) {
            foreach ($Statement as $row) {
                $ReturnString = implode("///", $row);
                $ReturnString .= "///";
            }
                $ReturnString .= $this->GetPrescriptionWithAId($id);
                $ReturnString .= $this->GetVaccineWithAId($id);
                $ReturnString .= $this->GetDoctorAId($id);
                return $ReturnString;
        }
        else
        {
            return null;
        }
    }
    public function GetPrescriptionWithAId(int $AId):string
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Prescription.Name
                                                         from Prescription
                                                         join Appointment on Prescription.AId = Appointment.Id
                                                         where Appointment.Id = :Id");
        if($Statement->execute([':Id' => $AId])) {
            if ($Statement->rowCount()) {
                foreach ($Statement as $row) {
                    $ReturnString .= implode("", $row);
                    $ReturnString .= ", ";
                }
                $ReturnString .= "///";
                return $ReturnString;
            }
        }
        $ReturnString .= "abcdefabc///";
        return $ReturnString;
    }
    public function GetVaccineWithAId(int $AId):string
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Vaccine.Name
                                                    from Vaccine
                                                    join Appointment on Vaccine.AId = Appointment.Id
                                                    where Appointment.Id = :Id");
        if($Statement->execute([':Id' => $AId]))
        {
            if ($Statement->rowCount()) {
                foreach ($Statement as $row) {
                    $ReturnString .= implode("", $row);
                    $ReturnString .= ", ";
                }
                $ReturnString .= "///";
                return $ReturnString;
            }
        }
        $ReturnString .= "abcdefabc///";
        return $ReturnString;
    }
    public function GetAppointmentsPictures(int $id)
    {
        $a = "";
        $Statement = $this->pdo->prepare("select Appointment.Picture
                                                    from Appointment
                                                    where Appointment.UId = :UId");
        if($Statement->execute([':UId' => $id]))
        {
            foreach($Statement->fetch() as $row)
            {
                echo $row['Picture'];
            }
            return null;
        }
        else
        {
            return null;
        }
    }
}