<?php
//
//  Module: LotteryServiceRouter.php - G.J. Watson
//    Desc: Route to appropriate response
// Version: 1.21
//

    // first load up the common project code
    set_include_path("<LIB PATH GOES HERE>");
    require_once("Common.php");
    require_once("Database.php");
    require_once("JsonBuilder.php");
    require_once("ServiceException.php");
    require_once("Validate.php");
    require_once("UserAccess.php");
    require_once("GetAuthorisationToken.php");

    // objects
    require_once("objects/Draw.php");
    require_once("objects/Lottery.php");

    // common SQL statements
    require_once("sql/LotterySQL.php");

    // support functions
    //require_once("support/UpdateRandomQuoteTimesUsed.php");

    // functions to return json
    require_once("responses/GetLotteries.php");
    require_once("responses/GetLotteriesWithDraws.php");
    require_once("responses/GetLotteriesWithDrawsFromID.php");
    require_once("responses/GetLatestLotteryDraws.php");
    
    // connection details for database
    require_once("connect/Lottery.php");

    //
    // check it's a request we can deal with and then process appropriately
    //

    function routeRequest($check, $db, $access, $generated, $arr) {
        $version = "v1.20";
        switch ($arr["request"]) {
            case "alldraws":
                $jsonObj = new JSONBuilder($version, "GetLotteriesWithDraws", $generated, "lottery", getLotteriesWithDraws($db));
                break;
            case "limitdraws":
                if (! $check->checkVariableExistsInArray("draws", $arr) || ! $check->isValidNumeric($arr["draws"])) {
                    throw new ServiceException(ILLEGALDRAWCOUNT["message"], ILLEGALDRAWCOUNT["code"]);
                }
                $jsonObj = new JSONBuilder($version, "GetLotteriesWithLimitedDraws", $generated, "lottery", getLotteriesWithDraws($db, $arr["draws"]));
                break;
            case "lotteries":
                $jsonObj = new JSONBuilder($version, "GetLotteries", $generated, "lottery", getLotteries($db));
                break;
            case "lotterybyid":
                if (! $check->checkVariableExistsInArray("id", $arr) || ! $check->isValidNumeric($arr["id"])) {
                    throw new ServiceException(ILLEGALLOTTERYID["message"], ILLEGALLOTTERYID["code"]);
                }
                $jsonObj = new JSONBuilder($version, "GetLotteryByID", $generated, "lottery", getLotteriesWithDrawsFromID($db, $arr["id"]));
                break;
            case "latestdraws":
                $jsonObj = new JsonBuilder($version, "GetLatestDraws", $generated, "lottery", getLatestLotteryDraws($db));
                break;
            default:
                throw new ServiceException(HTTPROUTINGERROR["message"], HTTPROUTINGERROR["code"]);
        }
        return $jsonObj->getJson();
    }

    //
    // 1. do we have a valid token
    // 2. route the request
    // 3. log the access
    //

    $db = new Database($database, $username, $password, $hostname);
    $htmlCode = 200;
    $htmlMess = "200 OK";
    $response = "";
    try {
        $common = new Common();
        $db->connect();
        //
        // 1 - token authorisation exists in https headers and is the right length/format, hasn't been abused etc
        //
        $check = new Validate();
        $token = getAuthorisationTokenFromHeaders($check);
        $access = new UserAccess($token);
        $access->checkAccessAllowed($db);
        // 2 - routing
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                if (! $check->checkVariableExistsInArray("request", $_GET)) {
                    throw new ServiceException(MALFORMEDREQUEST["message"], MALFORMEDREQUEST["code"]);
                }
                $response = routeRequest($check, $db, $access, $common->getGeneratedDateTime(), $_GET);
                break;
            case "POST":
            case "PUT":
            case "DELETE":
                throw new ServiceException(HTTPSUPPORTERROR["message"], HTTPSUPPORTERROR["code"]);
                break;
            default:
                throw new ServiceException(HTTPMETHODERROR["message"], HTTPMETHODERROR["code"]);
        }
        // 3 - log req
        $access->logRequest($db, $_SERVER['REMOTE_ADDR']);
        $db->close();
    } catch (ServiceException $e) {
        // set the html code and message depending on Exception
        $htmlCode = $e->getHTMLResponseCode();
        $htmlMess = $e->getHTMLResponseMsg();
        $response = $e->jsonString();
    } catch (Exception $e) {
        throw new ServiceException(UNKNOWNERROR["message"], UNKNOWNERROR["code"]);
    } finally {
        // send the result of the req back
        header_remove();
        http_response_code($htmlCode);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header("Content-type: application/json;charset=utf-8");
        header("Status: ".$htmlMess);
        echo $response;
    }
?>
