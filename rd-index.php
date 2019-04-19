<?php
// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    // https://www.php.net/manual/en/function.date-diff.php
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat);
}

function convertDate($s)
{
    $dateformat = strtotime($s);
    $dateformat = substr(date('m/d/Y',$dateformat),0,5);
    return $dateformat;
}

function sizeWarning($age,$size)
{
    $alert = false;
    switch ($size) {
        /**
        case "1":
            if($age >= 2){
                $alert = true;
            }
            break;             */
        case "5":
            if($age >= 3){
                $alert = true;
            }
            break;
        case "25":
            if($age >= 4){
                $alert = true;
            }
            break;
        default:
            $alert = true;
            break;
    }
    return $alert;
}

function sizeAlert($age,$size)
{
    $alert = false;
    switch ($size) {
        case "1":
            if($age >= 2){
                $alert = true;
            }
            break;
        case "5":
            if($age >= 4){
                $alert = true;
            }
            break;
        case "25":
            if($age >= 6){
                $alert = true;
            }
            break;
        default:
            $alert = true;
            break;
    }
    return $alert;
}

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "rdunn@nctconline.org:Louisburg88");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function lookupOwner($id)
{
    $label = "";

    switch ($id) {
        case "793269431":
            $label = "CS";
            break;
        case "377548710":
            $label = "MB";
            break;
        case "251485278":
            $label = "TH";
            break;
        case "369916551":
            $label = "AS";
            break;
        case "793269438":
            $label = "BR";
            break;
        case "782490955":
            $label = "HE";
            break;
        case "248072499":
            $label = "JS";
            break;
        default:
            $label = "??";
            break;
    }
    return $label;

}

function buildDoingLaneAnalysis()
{
    $data = CallAPI("GET", "https://nctc.leankit.com/kanban/api/boards/793475984");
    $character = json_decode($data);

    $card_count_cs = $size_totel_cs =
    $card_count_mb = $size_totel_mb =
    $card_count_th = $size_totel_th =
    $card_count_as = $size_totel_as =
    $card_count_br = $size_totel_br =
    $card_count_he = $size_totel_he =
    $card_count_js = $size_totel_js =
    $card_count_zz = $size_totel_zz = 0;

    // analyze "doing lane"
    $card_array = $character->ReplyData[0]->Lanes[3]->Cards;
    foreach ($card_array as $card) {
        // echo "Card Id: ".$card->Id."\n";
        //echo "Size: ".$card->Size."\n";
        //echo "Assigned User: ".$card->AssignedUserId."\n";
        switch ($card->AssignedUserId) {
            case "793269431":
                $card_count_cs ++;
                $size_totel_cs = $size_totel_cs + $card->Size;
                break;
            case "377548710":
                $card_count_mb ++;
                $size_totel_mb = $size_totel_mb + $card->Size;

                break;
            case "251485278":
                $card_count_th ++;
                $size_totel_th = $size_totel_th + $card->Size;
                break;
            case "369916551":
                $card_count_as ++;
                $size_totel_as = $size_totel_as + $card->Size;
                break;
            case "793269438":
                $card_count_br ++;
                $size_totel_br = $size_totel_br + $card->Size;
                break;
            case "782490955":
                $card_count_he ++;
                $size_totel_he = $size_totel_he + $card->Size;
                break;
            case "248072499":
                $card_count_js ++;
                $size_totel_js = $size_totel_js + $card->Size;
                break;
            default:
                $card_count_zz ++;
                $size_totel_zz = $size_totel_zz + $card->Size;
                break;
        }
    }

    if($card_count_cs==0){$cs_alert = "nk-red";}else{$cs_alert = "";}
    if($card_count_mb==0){$mb_alert = "nk-red";}else{$mb_alert = "";}
    if($card_count_th==0){$th_alert = "nk-red";}else{$th_alert = "";}
    if($card_count_as==0){$as_alert = "nk-red";}else{$as_alert = "";}
    if($card_count_br==0){$br_alert = "nk-red";}else{$br_alert = "";}
    if($card_count_he==0){$he_alert = "nk-red";}else{$he_alert = "";}
    if($card_count_js==0){$js_alert = "nk-red";}else{$js_alert = "";}
    //if($card_count_zz==0){$zz_alert = "nk-red";}else{$zz_alert = "";}

    echo "<tr class=\"".$cs_alert."\"><td>CS</td><td>$card_count_cs</td><td>$size_totel_cs</td></tr>";
    echo "<tr class=\"".$mb_alert."\"><td>MB</td><td>$card_count_mb</td><td>$size_totel_mb</td></tr>";
    echo "<tr class=\"".$th_alert."\"><td>TH</td><td>$card_count_th</td><td>$size_totel_th</td></tr>";
    echo "<tr class=\"".$as_alert."\"><td>AS</td><td>$card_count_as</td><td>$size_totel_as</td></tr>";
    echo "<tr class=\"".$br_alert."\"><td>BR</td><td>$card_count_br</td><td>$size_totel_br</td></tr>";
    echo "<tr class=\"".$he_alert."\"><td>HE</td><td>$card_count_he</td><td>$size_totel_he</td></tr>";
    echo "<tr class=\"$js_alert\"><td>JS</td><td>$card_count_js</td><td>$size_totel_js</td></tr>";
    //echo "<tr class=\"".$zz_alert."\"><td>ZZ</td><td>$card_count_zz</td><td>$size_totel_zz</td></tr>";
}

function buildDoneLaneAnalysis()
{
    $data = CallAPI("GET", "https://nctc.leankit.com/kanban/api/boards/793475984");
    $character = json_decode($data);

    $card_count_cs = $size_totel_cs =
    $card_count_mb = $size_totel_mb =
    $card_count_th = $size_totel_th =
    $card_count_as = $size_totel_as =
    $card_count_br = $size_totel_br =
    $card_count_he = $size_totel_he =
    $card_count_js = $size_totel_js =
    $card_count_zz = $size_totel_zz = 0;

    // analyze "doing lane"
    $card_array = $character->ReplyData[0]->Lanes[4]->Cards;
    foreach ($card_array as $card) {
        // echo "Card Id: ".$card->Id."\n";
        //echo "Size: ".$card->Size."\n";
        //echo "Assigned User: ".$card->AssignedUserId."\n";
        switch ($card->AssignedUserId) {
            case "793269431":
                $card_count_cs ++;
                $size_totel_cs = $size_totel_cs + $card->Size;
                break;
            case "377548710":
                $card_count_mb ++;
                $size_totel_mb = $size_totel_mb + $card->Size;
                break;
            case "251485278":
                $card_count_th ++;
                $size_totel_th = $size_totel_th + $card->Size;
                break;
            case "369916551":
                $card_count_as ++;
                $size_totel_as = $size_totel_as + $card->Size;
                break;
            case "793269438":
                $card_count_br ++;
                $size_totel_br = $size_totel_br + $card->Size;
                break;
            case "782490955":
                $card_count_he ++;
                $size_totel_he = $size_totel_he + $card->Size;
                break;
            case "248072499":
                $card_count_js ++;
                $size_totel_js = $size_totel_js + $card->Size;
                break;
            default:
                $card_count_zz ++;
                $size_totel_zz = $size_totel_zz + $card->Size;
                break;
        }
    }

    echo "<tr><td>CS</td><td>$card_count_cs</td><td>$size_totel_cs</td></tr>";
    echo "<tr><td>MB</td><td>$card_count_mb</td><td>$size_totel_mb</td></tr>";
    echo "<tr><td>TH</td><td>$card_count_th</td><td>$size_totel_th</td></tr>";
    echo "<tr><td>AS</td><td>$card_count_as</td><td>$size_totel_as</td></tr>";
    echo "<tr><td>BR</td><td>$card_count_br</td><td>$size_totel_br</td></tr>";
    echo "<tr><td>HE</td><td>$card_count_he</td><td>$size_totel_he</td></tr>";
    echo "<tr><td>JS</td><td>$card_count_js</td><td>$size_totel_js</td></tr>";
    //echo "<tr><td>ZZ</td><td>$card_count_zz</td><td>$size_totel_zz</td></tr>";
}

function buildPausedCards()
{
    $data = CallAPI("GET", "https://nctc.leankit.com/kanban/api/boards/793475984");
    $character = json_decode($data);
    $card_array = $character->ReplyData[0]->Lanes[0]->Cards;
    foreach ($card_array as $card) {
        // echo "Card Id: ".$card->Id."\n";
        //echo "Card Title: ".$card->Title."\n";
        //echo "Last Moved: ".$card->LastMove."\n";
        //echo "Last Moved: ".convertDate($card->LastMove)."\n";
        //echo "Age: ".dateDifference(convertDate($card->LastMove),date('Y-m-d'))."\n";
        //echo "Size: ".$card->Size."\n";
        //echo "Assigned User: ".$card->AssignedUserId."\n";
        // link to card ... https://nctc.leankit.com/card/820684943
        //echo " --- \n";

        echo "<!-- *** CARD STARTS HERE *** -->";
        echo "<div class=\"col-lg-3 col-md-6 col-sm-6 col-xs-12\" style=\"padding: 10px;\" >";

        $card_color = "#607D8B";
        $card_note = "";
        if(sizeAlert(dateDifference(convertDate($card->LastMove),date('Y-m-d')),$card->Size))
        {
            //$card_color = "#FF5722";
            $card_note = "Taking too long.";
        }else{
            if(sizeWarning(dateDifference(convertDate($card->LastMove),date('Y-m-d')),$card->Size))
            {
                $card_color = "#607D8B";
                $card_note = "Should be completed soon.";
            }
        }

        echo "    <div class=\"contact-list\" style=\"background-color: ".$card_color."\" >";
        echo "        <div class=\"contact-win\" style=\"float:right\">";
        echo "            <div class=\"conct-sc-ic\">";
        echo "                <a class=\"btn\" style=\"background-color: #999; color: black\">".lookupOwner($card->AssignedUserId)."</a>";
        echo "                <a class=\"btn\" style=\"background-color: #999; color: black\" >".$card->Size."</a>";
        echo "            </div>";
        echo "        </div>";
        echo "        <div class=\"contact-ctn\">";
        echo "            <div class=\"contact-ad-hd\">";
        echo "                <h2 style=\"color:white\">".substr($card->Title,0,40)." </h2>";
        echo "            </div>";
        echo "        </div>";
        echo "        <div class=\"social-st-list\">";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">Updated:</h2>";
        echo "                <p style=\"color:white\">".convertDate($card->LastMove)."</p>";
        echo "            </div>";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">Age:</h2>";
        echo "                <p style=\"color:white\">".dateDifference(convertDate($card->LastMove),date('Y-m-d'))."d</p>";
        echo "            </div>";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">LK:</h2>";
        echo "                <p ><a style=\"color:white\" target=\"_blank\" href=\"https://nctc.leankit.com/card/".$card->Id."\"><u>Link</u></a></p>";
        echo "            </div>";
        echo "        </div>";
        echo "    </div>";
        echo "</div>";
        echo "<!-- *** CARD ENDS HERE *** -->";

    }
}

function buildBlockedCards()
{
    $data = CallAPI("GET", "https://nctc.leankit.com/kanban/api/boards/793475984");
    $character = json_decode($data);
    $card_array = $character->ReplyData[0]->Lanes[2]->Cards;
    foreach ($card_array as $card) {
        // echo "Card Id: ".$card->Id."\n";
        //echo "Card Title: ".$card->Title."\n";
        //echo "Last Moved: ".$card->LastMove."\n";
        //echo "Last Moved: ".convertDate($card->LastMove)."\n";
        //echo "Age: ".dateDifference(convertDate($card->LastMove),date('Y-m-d'))."\n";
        //echo "Size: ".$card->Size."\n";
        //echo "Assigned User: ".$card->AssignedUserId."\n";
        // link to card ... https://nctc.leankit.com/card/820684943
        //echo " --- \n";

        echo "<!-- *** CARD STARTS HERE *** -->";
        echo "<div class=\"col-lg-3 col-md-6 col-sm-6 col-xs-12\" style=\"padding: 10px;\" >";

        $card_color = "#607D8B";
        $card_note = "";
        if(sizeAlert(dateDifference(convertDate($card->LastMove),date('Y-m-d')),$card->Size))
        {
            //$card_color = "#FF5722";
            $card_note = "Taking too long.";
        }else{
            if(sizeWarning(dateDifference(convertDate($card->LastMove),date('Y-m-d')),$card->Size))
            {
                //$card_color = "#607D8B";
                $card_note = "Should be completed soon.";
            }
        }

        echo "    <div class=\"contact-list\" style=\"background-color: ".$card_color."\" >";
        echo "        <div class=\"contact-win\" style=\"float:right\">";
        echo "            <div class=\"conct-sc-ic\">";
        echo "                <a class=\"btn\" style=\"background-color: #999; color: black\">".lookupOwner($card->AssignedUserId)."</a>";
        echo "                <a class=\"btn\" style=\"background-color: #999; color: black\" >".$card->Size."</a>";
        echo "            </div>";
        echo "        </div>";
        echo "        <div class=\"contact-ctn\">";
        echo "            <div class=\"contact-ad-hd\">";
        echo "                <h2 style=\"color:white\">".substr($card->Title,0,40)." </h2>";
        echo "            </div>";
        echo "        </div>";
        echo "        <div class=\"social-st-list\">";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">Updated:</h2>";
        echo "                <p style=\"color:white\">".convertDate($card->LastMove)."</p>";
        echo "            </div>";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">Age:</h2>";
        echo "                <p style=\"color:white\">".dateDifference(convertDate($card->LastMove),date('Y-m-d'))."d</p>";
        echo "            </div>";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">LK:</h2>";
        echo "                <p ><a style=\"color:white\" target=\"_blank\" href=\"https://nctc.leankit.com/card/".$card->Id."\"><u>Link</u></a></p>";
        echo "            </div>";
        echo "        </div>";
        echo "    </div>";
        echo "</div>";
        echo "<!-- *** CARD ENDS HERE *** -->";

    }
}

function buildCards()
{
    $data = CallAPI("GET", "https://nctc.leankit.com/kanban/api/boards/793475984");
    $character = json_decode($data);
    $card_array = $character->ReplyData[0]->Lanes[3]->Cards;
    foreach ($card_array as $card) {
        // echo "Card Id: ".$card->Id."\n";
        //echo "Card Title: ".$card->Title."\n";
        //echo "Last Moved: ".$card->LastMove."\n";
        //echo "Last Moved: ".convertDate($card->LastMove)."\n";
        //echo "Age: ".dateDifference(convertDate($card->LastMove),date('Y-m-d'))."\n";
        //echo "Size: ".$card->Size."\n";
        //echo "Assigned User: ".$card->AssignedUserId."\n";
        // link to card ... https://nctc.leankit.com/card/820684943
        //echo " --- \n";

        echo "<!-- *** CARD STARTS HERE *** -->";
        echo "<div class=\"col-lg-3 col-md-6 col-sm-6 col-xs-12\" style=\"padding: 10px;\" >";

        $card_color = "#607D8B";
        $card_note = "";
        if(sizeAlert(dateDifference(convertDate($card->LastMove),date('Y-m-d')),$card->Size))
        {
            $card_color = "#FF5722";
            $card_note = "Taking too long.";
        }else{
            if(sizeWarning(dateDifference(convertDate($card->LastMove),date('Y-m-d')),$card->Size))
            {
                $card_color = "#607D8B";
                $card_note = "Should be completed soon.";
            }
        }

        echo "    <div class=\"contact-list\" style=\"background-color: ".$card_color."\" >";
        echo "        <div class=\"contact-win\" style=\"float:right\">";
        echo "            <div class=\"conct-sc-ic\">";
        echo "                <a class=\"btn\" style=\"background-color: #999; color: black\">".lookupOwner($card->AssignedUserId)."</a>";
        echo "                <a class=\"btn\" style=\"background-color: #999; color: black\" >".$card->Size."</a>";
        echo "            </div>";
        echo "        </div>";
        echo "        <div class=\"contact-ctn\">";
        echo "            <div class=\"contact-ad-hd\">";
        echo "                <h2 style=\"color:white\">".substr($card->Title,0,40)." </h2>";
        echo "            </div>";
        echo "        </div>";
        echo "        <div class=\"social-st-list\">";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">Updated:</h2>";
        echo "                <p style=\"color:white\">".convertDate($card->LastMove)."</p>";
        echo "            </div>";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">Age:</h2>";
        echo "                <p style=\"color:white\">".dateDifference(convertDate($card->LastMove),date('Y-m-d'))."d</p>";
        echo "            </div>";
        echo "            <div class=\"social-sn\">";
        echo "                <h2 style=\"color:white\">LK:</h2>";
        echo "                <p ><a style=\"color:white\" target=\"_blank\" href=\"https://nctc.leankit.com/card/".$card->Id."\"><u>Link</u></a></p>";
        echo "            </div>";
        echo "        </div>";
        echo "    </div>";
        echo "</div>";
        echo "<!-- *** CARD ENDS HERE *** -->";

    }
}

// Lanes[0] - Paused
// Lanes[1] - Stopped Work
// Lanes[2] - Blocked
// Lanes[3] - Doing
// Lanes[4] - Done - 793484018

?>


<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Contact | Notika - Notika Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu/meanmenu.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Mobile Menu start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul class="mobile-menu-nav">
                                <li><a data-toggle="collapse" data-target="#Charts" href="#">Home</a>
                                    <ul class="collapse dropdown-header-top">
                                        <li><a href="index.html">Dashboard One</a></li>
                                        <li><a href="index-2.html">Dashboard Two</a></li>
                                        <li><a href="index-3.html">Dashboard Three</a></li>
                                        <li><a href="index-4.html">Dashboard Four</a></li>
                                        <li><a href="analytics.html">Analytics</a></li>
                                        <li><a href="widgets.html">Widgets</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demoevent" href="#">Email</a>
                                    <ul id="demoevent" class="collapse dropdown-header-top">
                                        <li><a href="inbox.html">Inbox</a></li>
                                        <li><a href="view-email.html">View Email</a></li>
                                        <li><a href="compose-email.html">Compose Email</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#democrou" href="#">Interface</a>
                                    <ul id="democrou" class="collapse dropdown-header-top">
                                        <li><a href="animations.html">Animations</a></li>
                                        <li><a href="google-map.html">Google Map</a></li>
                                        <li><a href="data-map.html">Data Maps</a></li>
                                        <li><a href="code-editor.html">Code Editor</a></li>
                                        <li><a href="image-cropper.html">Images Cropper</a></li>
                                        <li><a href="wizard.html">Wizard</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demolibra" href="#">Charts</a>
                                    <ul id="demolibra" class="collapse dropdown-header-top">
                                        <li><a href="flot-charts.html">Flot Charts</a></li>
                                        <li><a href="bar-charts.html">Bar Charts</a></li>
                                        <li><a href="line-charts.html">Line Charts</a></li>
                                        <li><a href="area-charts.html">Area Charts</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demodepart" href="#">Tables</a>
                                    <ul id="demodepart" class="collapse dropdown-header-top">
                                        <li><a href="normal-table.html">Normal Table</a></li>
                                        <li><a href="data-table.html">Data Table</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demo" href="#">Forms</a>
                                    <ul id="demo" class="collapse dropdown-header-top">
                                        <li><a href="form-elements.html">Form Elements</a></li>
                                        <li><a href="form-components.html">Form Components</a></li>
                                        <li><a href="form-examples.html">Form Examples</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">App views</a>
                                    <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                        <li><a href="notification.html">Notifications</a>
                                        </li>
                                        <li><a href="alert.html">Alerts</a>
                                        </li>
                                        <li><a href="modals.html">Modals</a>
                                        </li>
                                        <li><a href="buttons.html">Buttons</a>
                                        </li>
                                        <li><a href="tabs.html">Tabs</a>
                                        </li>
                                        <li><a href="accordion.html">Accordion</a>
                                        </li>
                                        <li><a href="dialog.html">Dialogs</a>
                                        </li>
                                        <li><a href="popovers.html">Popovers</a>
                                        </li>
                                        <li><a href="tooltips.html">Tooltips</a>
                                        </li>
                                        <li><a href="dropdown.html">Dropdowns</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages</a>
                                    <ul id="Pagemob" class="collapse dropdown-header-top">
                                        <li><a href="contact.html">Contact</a>
                                        </li>
                                        <li><a href="invoice.html">Invoice</a>
                                        </li>
                                        <li><a href="typography.html">Typography</a>
                                        </li>
                                        <li><a href="color.html">Color</a>
                                        </li>
                                        <li><a href="login-register.html">Login Register</a>
                                        </li>
                                        <li><a href="404.html">404 Page</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    <div class="main-menu-area mg-tb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                        <li><a data-toggle="tab" href="#Home"><i class="notika-icon notika-house"></i> Home</a>
                        </li>
                        <li><a data-toggle="tab" href="#mailbox"><i class="notika-icon notika-mail"></i> Email</a>
                        </li>
                        <li><a data-toggle="tab" href="#Interface"><i class="notika-icon notika-edit"></i> Interface</a>
                        </li>
                        <li><a data-toggle="tab" href="#Charts"><i class="notika-icon notika-bar-chart"></i> Charts</a>
                        </li>
                        <li><a data-toggle="tab" href="#Tables"><i class="notika-icon notika-windows"></i> Tables</a>
                        </li>
                        <li><a data-toggle="tab" href="#Forms"><i class="notika-icon notika-form"></i> Forms</a>
                        </li>
                        <li><a data-toggle="tab" href="#Appviews"><i class="notika-icon notika-app"></i> App views</a>
                        </li>
                        <li class="active"><a data-toggle="tab" href="#Page"><i class="notika-icon notika-support"></i> Pages</a>
                        </li>
                    </ul>
                    <div class="tab-content custom-menu-content">
                        <div id="Home" class="tab-pane in notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="index.html">Dashboard One</a>
                                </li>
                                <li><a href="index-2.html">Dashboard Two</a>
                                </li>
                                <li><a href="index-3.html">Dashboard Three</a>
                                </li>
                                <li><a href="index-4.html">Dashboard Four</a>
                                </li>
                                <li><a href="analytics.html">Analytics</a>
                                </li>
                                <li><a href="widgets.html">Widgets</a>
                                </li>
                            </ul>
                        </div>
                        <div id="mailbox" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="inbox.html">Inbox</a>
                                </li>
                                <li><a href="view-email.html">View Email</a>
                                </li>
                                <li><a href="compose-email.html">Compose Email</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Interface" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="animations.html">Animations</a>
                                </li>
                                <li><a href="google-map.html">Google Map</a>
                                </li>
                                <li><a href="data-map.html">Data Maps</a>
                                </li>
                                <li><a href="code-editor.html">Code Editor</a>
                                </li>
                                <li><a href="image-cropper.html">Images Cropper</a>
                                </li>
                                <li><a href="wizard.html">Wizard</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Charts" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="flot-charts.html">Flot Charts</a>
                                </li>
                                <li><a href="bar-charts.html">Bar Charts</a>
                                </li>
                                <li><a href="line-charts.html">Line Charts</a>
                                </li>
                                <li><a href="area-charts.html">Area Charts</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Tables" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="normal-table.html">Normal Table</a>
                                </li>
                                <li><a href="data-table.html">Data Table</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Forms" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="form-elements.html">Form Elements</a>
                                </li>
                                <li><a href="form-components.html">Form Components</a>
                                </li>
                                <li><a href="form-examples.html">Form Examples</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Appviews" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="notification.html">Notifications</a>
                                </li>
                                <li><a href="alert.html">Alerts</a>
                                </li>
                                <li><a href="modals.html">Modals</a>
                                </li>
                                <li><a href="buttons.html">Buttons</a>
                                </li>
                                <li><a href="tabs.html">Tabs</a>
                                </li>
                                <li><a href="accordion.html">Accordion</a>
                                </li>
                                <li><a href="dialog.html">Dialogs</a>
                                </li>
                                <li><a href="popovers.html">Popovers</a>
                                </li>
                                <li><a href="tooltips.html">Tooltips</a>
                                </li>
                                <li><a href="dropdown.html">Dropdowns</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Page" class="tab-pane active notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="contact.html">Contact</a>
                                </li>
                                <li><a href="invoice.html">Invoice</a>
                                </li>
                                <li><a href="typography.html">Typography</a>
                                </li>
                                <li><a href="color.html">Color</a>
                                </li>
                                <li><a href="login-register.html">Login Register</a>
                                </li>
                                <li><a href="404.html">404 Page</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Menu area End-->
    <!-- Contact area Start-->
    <div class="contact-area">
        <div class="container">


            <div class="row">

            </div>

            <div class="row">

            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="accordion-wn-wp mg-t-30">
                        <div class="accordion-hd">
                            <h2>Kanban Analysis</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="accordion-stn sm-res-mg-t-30">
                                    <div class="panel-group" data-collapse-color="nk-blue" id="accordionBlue" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-collapse notika-accrodion-cus">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordionBlue" href="#accordionBlue-one" aria-expanded="true">
                                                            Paused Cards
                                                        </a>
                                                </h4>
                                            </div>
                                            <div id="accordionBlue-one" class="collapse animated flipInX in" role="tabpanel">
                                                <div class="panel-body">
                                                    <?php buildPausedCards(); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-collapse notika-accrodion-cus">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionBlue" href="#accordionBlue-two" aria-expanded="false">
                                                            Blocked Cards
                                                        </a>
                                                </h4>
                                            </div>
                                            <div id="accordionBlue-two" class="collapse animated flipInX" role="tabpanel">
                                                <div class="panel-body">
                                                    <?php buildBlockedCards(); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-collapse notika-accrodion-cus">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionBlue" href="#accordionBlue-three" aria-expanded="false">
                                                            Doing Cards
                                                        </a>
                                                </h4>
                                            </div>
                                            <div id="accordionBlue-three" class="collapse animated flipInX" role="tabpanel">
                                                <div class="panel-body">
                                                    <?php buildCards(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="normal-table-list mg-t-30">
                        <div class="basic-tb-hd">
                            <h2>Doing Lane Analysis</h2>
                        </div>
                        <div class="bsc-tbl-hvr">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Card</th>
                                        <th>Size</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php buildDoingLaneAnalysis(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="normal-table-list mg-t-30">
                        <div class="basic-tb-hd">
                            <h2>Done Lane Analysis</h2>
                        </div>
                        <div class="bsc-tbl-hvr">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Card</th>
                                        <th>Size</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php buildDoneLaneAnalysis(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact area End-->
    <!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2018
. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer area-->
    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="js/counterup/jquery.counterup.min.js"></script>
    <script src="js/counterup/waypoints.min.js"></script>
    <script src="js/counterup/counterup-active.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>
    <script src="js/flot/flot-active.js"></script>
    <!-- knob JS
		============================================ -->
    <script src="js/knob/jquery.knob.js"></script>
    <script src="js/knob/jquery.appear.js"></script>
    <script src="js/knob/knob-active.js"></script>
    <!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
    <!--  Chat JS
		============================================ -->
    <script src="js/chat/jquery.chat.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="js/todo/jquery.todo.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
</body>

</html>
