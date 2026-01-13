function logOutValidation()
{
    var ask = window.confirm("Are you sure to log out?");
    if (ask == true)
    {
        window.location="database/logOut_process.php"
    }
    else
    {
        return false;
    }
}

function staffDeleteValidation()
{
    var ask = window.confirm("Are you sure to proceed delete staff data?");
    if (ask == true)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function updateAdminPswValidation() 
{
    var password = document.getElementById("newPsw").value;
    var confirmPassword = document.getElementById("confirmPsw").value;

    if (password != confirmPassword) {
        alert("New password and confirm password does not match!.");
        return false;
    }
    else
    {
        return true;
    }
}
