<?php
include('Account.php');
include('User.php');
include('Doctor.php');

include('Signup.php');
include('Login.php');
include('DoctorSubmit.php');
include('DoctorList.php');
include('GetDoctor.php');
include('UpdateDoctor.php');
include('AppointmentList.php');
include('AppointmentSubmit.php');
include('PrescriptionSubmit.php');
include('VaccineSubmit.php');
include('AppointmentGet.php');

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
else if($_POST['Function'] === 'DoctorForm')
{
    $DoctorForm = new DoctorSubmit();
    $error = $DoctorForm->handle();
    echo $error;
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
else if($_POST['Function'] === 'UpdateDoctor')
{
    $Doctor = new UpdateDoctor();
    $error = $Doctor->handle();
    echo $error;
}
else if($_POST['Function'] === 'ListAppointments')
{
    $Appointments = new AppointmentList();
    $error = $Appointments->handle();
    echo $error;
}
else if($_POST['Function'] === 'AddAppointment')
{
    $Appointment = new AppointmentSubmit();
    $error = $Appointment->handle();
    echo $error;
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