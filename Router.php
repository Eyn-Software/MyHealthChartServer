<?php
include('Account.php');
include('User.php');
include('Doctor.php');

include('Signup.php');
include('Login.php');
include('UserAdd.php');
include('DoctorSubmit.php');
include('DoctorList.php');
include('GetDoctor.php');
include('DoctorUpdate.php');
include('AppointmentList.php');
include ('AppointmentListAll.php');
include('AppointmentSubmit.php');
include('AppointmentUpdate.php');
include('AppointmentGet.php');
include('AppointmentListFuture.php');
include('PrescriptionSubmit.php');
include('VaccineSubmit.php');
include('ConditionList.php');
include('ConditionSubmit.php');
include('ConditionDelete.php');
include('AllergyList.php');
include('AllergySubmit.php');
include('AllergyDelete.php');
include('VaccineList.php');
include('PrescriptionList.php');
include('PrescriptionGet.php');
include('PrescriptionUpdate.php');
include('FolderGetRoot.php');
include('FolderGetChildren.php');
include('FolderGetNotes.php');
include('FolderSubmit.php');
include('NoteSubmit.php');
include('NoteGet.php');
include('NoteDelete.php');
include('NoteUpdate.php');

include('Authorization.php');
include('Database.php');

if(!$_SERVER['REQUEST_METHOD'] === 'POST')
{
    echo "Request Type Error";
}
else if($_POST['Function'] === 'Login')
{
    $Login = new Login();
    $error = $Login->handle();
    echo $error;
}
else if($_POST['Function'] === 'Register')
{
    $SignUp = new Signup();
    $error = $SignUp->handle();
    echo $error;
}
else if($_POST['Function'] === 'DoctorList')
{
    $DoctorList = new DoctorList();
    $error = $DoctorList->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListAppointments')
{
    $Appointments = new AppointmentList();
    $error = $Appointments->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListAllAppointments')
{
    $Appointments = new AppointmentListAll();
    $error = $Appointments->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListFutureAppointments')
{
    $Appointments = new AppointmentListFuture();
    $error = $Appointments->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListConditions')
{
    $Conditions = new ConditionList();
    $error = $Conditions->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListAllergies')
{
    $Allergies = new AllergyList();
    $error = $Allergies->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListVaccines')
{
    $Vaccines = new VaccineList();
    $error = $Vaccines->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListPrescriptions')
{
    $Prescriptions = new PrescriptionList();
    echo $Prescriptions->handle();
}
else if($_POST['Function'] === 'ListChildFolders')
{
    $Folders = new FolderGetChildren();
    echo $Folders->handle();
}
else if($_POST['Function'] === 'ListChildNotes')
{
    $Notes = new FolderGetNotes();
    echo $Notes->handle();
}
else if($_POST['Function'] === 'GetDoctor')
{
    $Doctor = new GetDoctor();
    $error = $Doctor->handle();
    echo $error;
}
else if($_POST['Function'] === 'GetAppointment')
{
    $Appointment = new AppointmentGet();
    $error = $Appointment->handle();
    echo $error;
}
else if($_POST['Function'] === 'GetPrescription')
{
    $Prescription = new PrescriptionGet();
    $error = $Prescription->handle();
    echo $error;
}
else if($_POST['Function'] === 'GetRootFolder')
{
    $Folder = new FolderGetRoot();
    $error = $Folder->handle();
    echo $error;
}
else if($_POST['Function'] === 'GetNote')
{
    $Note = new NoteGet();
    $error = $Note->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddUser')
{
    $User = new UserAdd();
    $error = $User->handle();
    echo $error;
}
else if($_POST['Function'] === 'DoctorForm')
{
    $DoctorForm = new DoctorSubmit();
    $error = $DoctorForm->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddAppointment')
{
    $Appointment = new AppointmentSubmit();
    $error = $Appointment->handle();
    echo $error;
}
else if($_POST['Function'] === 'AppointmentPicture')
{
    echo 'b';
}
else if($_FILES['AppointmentPicture'])
{
    echo 'c';
}
else if($_POST['Function'] === 'AddPrescription')
{
    $Prescription = new PrescriptionSubmit();
    $error = $Prescription->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddVaccine')
{
    $Vaccine = new VaccineSubmit();
    $error = $Vaccine->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddCondition')
{
    $Condition = new ConditionSubmit();
    $error = $Condition->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddAllergy')
{
    $Allergy = new AllergySubmit();
    $error = $Allergy->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddFolder')
{
    $Folder = new FolderSubmit();
    $error = $Folder->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddNote')
{
    $Note = new NoteSubmit();
    $error = $Note->handle();
    echo $error;
}
else if($_POST['Function'] === 'UpdateDoctor')
{
    $Doctor = new DoctorUpdate();
    $error = $Doctor->handle();
    echo $error;
}
else if($_POST['Function'] === 'UpdateAppointment')
{
    $Appointment = new AppointmentUpdate();
    $error = $Appointment->handle();
    echo $error;
}
else if($_POST['Function'] === 'UpdatePrescription')
{
    $Prescription = new PrescriptionUpdate();
    $error = $Prescription->handle();
    echo $error;
}
else if($_POST['Function'] === 'UpdateNote')
{
    $Note = new NoteUpdate();
    $error = $Note->handle();
    echo $error;
}
else if($_POST['Function'] === 'DeleteCondition')
{
    $Condition = new ConditionDelete();
    $error = $Condition->handle();
    echo $error;
}
else if($_POST['Function'] == 'DeleteAllergy')
{
    $Allergy = new AllergyDelete();
    $error = $Allergy->handle();
    echo $error;
}
else if($_POST['Function'] === 'DeleteNote')
{
    $Note = new NoteDelete();
    $error = $Note->handle();
    echo $error;
}
else
{
    echo $_POST['Image'];
}