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
    //Adds a user and returns them
    public function AddUserWGet(string $Name, string $Birthday, int $AId)
    {
        $Statement = $this->pdo->prepare("insert into User(Name, Birthday, AId)
                                                    values (:Name, :Birthday, :AId)");
        $Statement->execute([
            ':Name' => $Name,
            ':Birthday' => $Birthday,
            ':AId' => $AId
        ]);
        $Statement = $this->pdo->prepare("select * from User 
                                                    where Id = :Id");
        $Statement->execute([
            ':Id' => (int)$this->pdo->lastInsertId()
        ]);
        $ReturnString = "";
        foreach($Statement as $row)
        {
            $ReturnString = implode("///", $row);
            $ReturnString .= "///";
        }
        return $ReturnString;
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
            return $this->pdo->lastInsertId();
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
    public function AddCondition(string $Type, int $UId)
    {
        $Statement = $this->pdo->prepare("insert into Conditions(Type) values (:Type)");
        $Statement->execute([
            ':Type' => $Type
        ]);
        if (strcmp($Statement->errorCode(), "23000") === 0 || $Statement->rowCount()) {
            $Statement = $this->pdo->prepare("insert into Conditions_User_Junction(CId, UId)
                                                values(:Type, :UId)");
            $Statement->execute([
                ':Type' => $Type,
                ':UId' => $UId
            ]);
            if (!$Statement->rowCount()) {
                return $Statement->errorCode();
            } else {
                return 'Success';
            }
        }
        return $Statement->errorInfo();
    }
    public function AddAllergy(string $Type, int $UId)
    {
        $Statement = $this->pdo->prepare("insert into Allergy(Type) values (:Type)");
        $Statement->execute([
            ':Type' => $Type
        ]);
        if(strcmp($Statement->errorCode(), "23000") === 0 || $Statement->rowCount())
        {
            $Statement = $this->pdo->prepare("insert into User_Allergy_Junction(AId, UId)
                                                        values(:Type, :UId)");
            $Statement->execute([
                ':Type' => $Type,
                ':UId' => $UId
            ]);
            if(!$Statement->rowCount())
            {
                return $Statement->errorCode();
            }
            else
            {
                return 'Success';
            }
        }
        return $Statement->errorInfo();
    }
    public function AddRootFolder(int $UId)
    {
        $Statement = $this->pdo->prepare("insert into Folder(UId, ParentFolderId, IsRoot)
                                                    values(:UId, :PId, :IsRoot)");
        $Statement->execute([
            ':UId' => $UId,
            ':PId' => 0,
            ':IsRoot' => true
        ]);
    }
    public function AddFolder(int $UId, string $Name, string $CreationDate, int $ParentFolderId)
    {
        $Statement = $this->pdo->prepare("insert into Folder(UId, Name, CreationDate, ParentFolderId)
                                                    values(:UId, :Name, :CreationDate, :PId)");
        $Statement->execute([
            ':UId' => $UId,
            ':Name' => $Name,
            ':CreationDate' => $CreationDate,
            ':PId' => $ParentFolderId
        ]);
        if($Statement->rowCount())
        {
            return "Success";
        }
        else
        {
            return "Failure";
        }
    }
    public function AddNote(int $UId, string $Name, string $Description,string $CreationDate,
                            int $ParentFolderId)
    {
        $Statement = $this->pdo->prepare("insert into Note(UId, Name, Description, CreationDate, 
                                                                        ParentFolderId)
                                                     values(:UId, :Name, :Description, :CreationDate,
                                                            :PId)");
        $Statement->execute([
            ':UId'=> $UId,
            ':Name' => $Name,
            ':Description' => $Description,
            ':CreationDate' => $CreationDate,
            ':PId' => $ParentFolderId
        ]);
        if($Statement->rowCount())
        {
            return 'Success';
        }
        else
        {
            return 'Failure';
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
    public function UpdateAppointment(int $Id, string $Date, string $Reason, string $Diagnosis, string $Aftercare)
    {
        $Statement = $this->pdo->prepare("update Appointment
                                          set Date = :Date, Reason = :Reason, Diagnosis = :Diagnosis,
                                          Aftercare = :Aftercare 
                                          where Id = :Id");
        $Statement->execute([
            ':Id' => $Id,
            ':Date' => $Date,
            ':Reason' => $Reason,
            ':Diagnosis' => $Diagnosis,
            ':Aftercare' => $Aftercare]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorInfo();
        }
        return 'Success';
    }
    public function UpdatePrescription(int $Id, string $Name, string $StartDate, string $EndDate, string $ReminderTime)
    {
        $Statement = $this->pdo->prepare("update Prescription
                                                    set Name = :Name, StartDate = :StartDate,
                                                        EndDate = :EndDate, ReminderTime = :ReminderTime
                                                    where Id = :Id");
        $Statement->execute([
            ':Id' => $Id,
            ':Name' => $Name,
            ':StartDate' => $StartDate,
            ':EndDate' => $EndDate,
            ':ReminderTime' => $ReminderTime
        ]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorInfo();
        }
        return 'Success';
    }
    public function UpdateNote(int $Id, string $Name, string $Description)
    {
        $Statement = $this->pdo->prepare("Update Note
                                                    set Name = :Name, Description = :Description
                                                    where Id = :Id");
        $Statement->execute([
            ':Id' => $Id,
            ':Name' => $Name,
            ':Description' => $Description
        ]);
        if(!$Statement->rowCount())
        {
            return null;
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
    public function GetAppointment(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Appointment.Id, Doctor.Name, Appointment.Date, Doctor.Address,
                                                    Appointment.Reason, Appointment.Diagnosis, Appointment.Aftercare
                                                    from Appointment
                                                    join Doctor on Appointment.DId = Doctor.Id
                                                    where Appointment.Id = :Id");
        if($Statement->execute([':Id' => $id])){
            foreach ($Statement as $row) {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
        }
        $ReturnString .= $this->GetPrescriptionWithAId($id);
        $ReturnString .= $this->GetVaccineWithAId($id);
        return $ReturnString;
    }
    public function GetPrescription(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Prescription.Id, Prescription.Name, 
                                                    Prescription.StartDate, Prescription.EndDate, 
                                                    Prescription.ReminderTime, Doctor.Name as DName 
                                                    from Prescription 
                                                    join Doctor on Prescription.DId = Doctor.Id 
                                                    where Prescription.Id=:PId");
        if($Statement->execute([':PId' => $id]))
        {
            foreach($Statement as $row)
            {
                $ReturnString = implode("///", $row);
                $ReturnString .= "///";
                return $ReturnString;
            }
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
    public function GetRootFolder(int $Id):string
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Id
                                                    from Folder
                                                    where UId = :Id and IsRoot=true");
        if ($Statement->execute([':Id' => $Id]))
        {
            if($Statement->rowCount())
            {
                foreach($Statement as $row)
                {
                    $ReturnString .= implode("", $row);
                }
                return $ReturnString;
            }
            $this->AddRootFolder($Id);
            return $this->GetRootFolder($Id);
        }
    }
    public function GetNote(int $Id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Name, Description
                                                    from Note
                                                    where Id = :Id");
        if($Statement->execute([':Id' => $Id]))
        {
            if($Statement->rowCount())
            {
                foreach($Statement as $row)
                {
                    $ReturnString .= implode("///", $row);
                    $ReturnString .= "///";
                }
                return $ReturnString;
            }
        }
        return $Statement->errorInfo();
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
        $Statement = $this->pdo->prepare("select Appointment.Id, Doctor.Name, Appointment.Date, Doctor.Address,
                                                    Appointment.Reason, Appointment.Diagnosis, Appointment.Aftercare
                                                    from Appointment
                                                    join Doctor on Appointment.DId = Doctor.Id
                                                    where Appointment.UId = :UId");
        if ($Statement->execute([':UId' => $id])) {
            foreach ($Statement as $row) {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        } else {
            return null;
        }
    }
    public function GetFutureAppointments(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Appointment.Id, User.Name as UName, 
                                                           Doctor.Name, Appointment.ReminderTime
                                                    from Appointment
                                                    join Doctor on Appointment.DId = Doctor.Id
                                                    join User on Appointment.UId = User.Id
                                                    where Appointment.UId = :UId and Appointment.ReminderTime > sysdate()");
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
    public function GetAllAppointments(int $AId)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Id
                                         from User
                                         where AId = :AId");
        if($Statement->execute([':AId' => $AId]))
        {
            foreach($Statement as $row)
            {
                $User = (int)$row['Id'];
                $Statement1 = $this->pdo->prepare("select Appointment.Date, User.Name as UName, Doctor.Name
                                                             from Appointment
                                                             join Doctor on Appointment.DId = Doctor.Id
                                                             join User on Appointment.UId = User.Id
                                                             where Appointment.UId = :UId");
                if($Statement1->execute([':UId' => $User]))
                {
                    foreach($Statement1 as $row1)
                    {
                        $ReturnString .= implode("///", $row1);
                        $ReturnString .= "///";
                    }
                }
            }
        }
        return $ReturnString;
    }


    public function GetConditions(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Conditions.Type
                                                    from Conditions
                                                    join Conditions_User_Junction on Conditions_User_Junction.CId = Conditions.Type
                                                    where UId = :UId");
        if($Statement->execute(['UId' => $id])) {
            foreach ($Statement as $row) {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        else
        {
            return $Statement->errorInfo();
        }
    }
    public function GetAllergies(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Allergy.Type
                                                    from Allergy
                                                    join User_Allergy_Junction on User_Allergy_Junction.AId = Allergy.Type
                                                    where UId = :UId");
        if($Statement->execute(['UId' => $id]))
        {
            foreach ($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        else
        {
            return $Statement->errorInfo();
        }
    }
    public function GetVaccines(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Vaccine.Name, Vaccine.Date
                                                    from Vaccine
                                                    where UId = :UId");
        if($Statement->execute([':UId' => $id])) {
            foreach ($Statement as $row) {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        return $Statement->errorInfo();
    }
    public function GetPrescriptions(int $id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Prescription.Id, Prescription.Name, 
                                                    Prescription.StartDate, Prescription.EndDate, 
                                                    Prescription.ReminderTime, Doctor.Name as DName
                                                    from Prescription
                                                    join Doctor on Prescription.DId = Doctor.Id
                                                    where UId = :UId");
        if($Statement->execute([':UId' => $id]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        return $Statement->errorInfo();
    }
    public function GetChildFolders(int $Id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Id, Name, CreationDate
                                                    from Folder
                                                    where ParentFolderId = :Id");
        if($Statement->execute([':Id' => $Id]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        return $Statement->errorInfo();
    }
    public function GetChildNotes(int $Id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("select Id, Name, CreationDate
                                                    from Note
                                                    where ParentFolderId = :Id");
        if($Statement->execute([':Id' => $Id]))
        {
            foreach($Statement as $row)
            {
                $ReturnString .= implode("///", $row);
                $ReturnString .= "///";
            }
            return $ReturnString;
        }
        return $Statement->errorInfo();
    }
    public function DeleteCondition(int $Id, string $Type)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("delete
                                                    from Conditions_User_Junction
                                                    where UId = :UId and CId = :CId");
        $Statement->execute([
            ':UId' => $Id,
            ':CId' => $Type
        ]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorInfo();
        }
        return 'Success';
    }
    public function DeleteAllergy(int $Id, string $Type)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("delete
                                                    from User_Allergy_Junction
                                                    where UId = :UId and AId = :AId");
        $Statement->execute([
            ':UId' => $Id,
            ':AId' => $Type
        ]);
        if(!$Statement->rowCount())
        {
            return $Statement->errorCode();
        }
        return 'Success';
    }
    public function DeleteNote(int $Id)
    {
        $ReturnString = "";
        $Statement = $this->pdo->prepare("delete from Note
                                                    where Id = :Id");
        $Statement->execute([
            ':Id' => $Id
        ]);
        if(!$Statement->rowCount())
        {
            return (string)$Id;
        }
        return 'Success';
    }
}