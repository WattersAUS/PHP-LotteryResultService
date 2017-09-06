<?php
//
// Program: constants.php (2017-07-14) G.J. Watson
//
// Purpose: Setup for constants on
//
// Date       Version Note
// ========== ======= ====================================================
// 2017-07-14 v1.01   First cut of code
// 2017-08-11 v1.02   Added ERRORCODE for Query Service
//

// generic errors
const DATABASEERROR          = -9999;
const REAQMETHODERROR        = -9998;
const ACCESSTOKENMISSING     = -9997;
const INCORRECTTOKENSUPPLIED = -9996;
const ACCESSDENIED           = -9995;
const TOOMANYREQUESTS        = -9994;
const TOKENEXPIRED           = -9993;
const UNKNOWNERROR           = -9000;

// service call errors
const ILLEGALDRAWCOUNT       = -9800;
const ILLEGALAUTHORID        = -9700;

function serviceErrorMessage($error) {
    $message = "An unknown error has occured!";
    switch ($error) {
        case DATABASEERROR:
            $message = "A Database error has occured!";
            break;
        case REAQMETHODERROR:
            $message = "The service does not recognise this HTTP request!";
            break;
        case ACCESSTOKENMISSING:
            $message = "The supplied token to the service is blank!";
            break;
        case INCORRECTTOKENSUPPLIED:
            $message = "The service does not recognise this token as a valid user!";
            break;
        case ACCESSDENIED:
            $message = "Access to this service has been denied!";
            break;
        case TOOMANYREQUESTS:
            $message = "This token has been blocked for making over 100 requests within an hour! Access to the service will be suspended for 24 hours!";
            break;
        case TOKENEXPIRED:
            $message = "The supplied token has expired, please request another one!";
            break;
        case ILLEGALDRAWCOUNT:
            $message = "Draw count must be between 1 and 1000!";
            break;
        case ILLEGALAUTHORID:
            $message = "Author ID must be supplied and a numeric!";
            break;
        default:
            $message = "There has been an unknown error in the service!";
            break;
    }
    return "ERROR: ".$message;
}
?>
