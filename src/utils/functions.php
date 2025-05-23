<?php
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF'])==$pagename){
        echo " class='active' ";
    }
}

function isUserLoggedIn(){
    return isset($_SESSION["username"]) && isset($_SESSION["logged"]) && isset($_SESSION["admin"]) && $_SESSION["logged"] && !$_SESSION["admin"];
}

function isAdminLoggedIn(){
    return isset($_SESSION["logged"]) && isset($_SESSION["admin"]) && $_SESSION["logged"] && $_SESSION["admin"];
}

function registerLoggedUser($user){
    $_SESSION["username"] = $user["username"];
    $_SESSION["id"] = $user["id"];
    $_SESSION["admin"] = $user["admin"];            # $_SESSION["admin"] = 1 if user is admin, 0 otherwise
    $_SESSION["logged"] = 1;                         # $_SESSION["logged"] = 1 if user is logged
}

function uploadImage($path, $image){
    $imageName = basename($image["name"]);
    $fullPath = $path.$imageName;
    
    $maxKB = 500;
    $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
    $result = 0;
    $msg = "";

    $imageSize = getimagesize($image["tmp_name"]);
    if($imageSize === false) {
        $msg .= "File caricato non è un'immagine! ";
    }

    if ($image["size"] > $maxKB * 1024) {
        $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
    }


    $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
    if(!in_array($imageFileType, $acceptedExtensions)){
        $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
    }


    if (file_exists($fullPath)) {
        $i = 1;
        do{
            $i++;
            $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
        }
        while(file_exists($path.$imageName));
        $fullPath = $path.$imageName;
    }


    if(strlen($msg)==0){
        if(!move_uploaded_file($image["tmp_name"], $fullPath)){
            $msg.= "Errore nel caricamento dell'immagine.";
        }
        else{
            $result = 1;
            $msg = "images/" . $imageName;
        }
    }
    return array($result, $msg);
}

function uploadFile($path, $file){
    $fileName = basename($file["name"]);
    $fullPath = $path . $fileName;

    $maxKB = 1024;
    $acceptedExtensions = array("txt", "pdf");
    $result = 0;
    $msg = "";

    // Controllo dimensione del file < 2MB
    if ($file["size"] > $maxKB * 1024) {
        $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
    }


    $fileType = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    if(!in_array($fileType, $acceptedExtensions)){
        $msg .= "Accettate solo le seguenti estensioni: ".implode(", ", $acceptedExtensions);
    }


    if (file_exists($fullPath)) {
        $i = 1;
        do {
            $i++;
            $fileName = pathinfo(basename($file["name"]), PATHINFO_FILENAME) . "_$i." . $fileType;
        } while(file_exists($path . $fileName));
        $fullPath = $path . $fileName;
    }


    if(strlen($msg) == 0){
        if(!move_uploaded_file($file["tmp_name"], $fullPath)){
            $msg .= "Errore nel caricamento del file.";
        } else {
            $result = 1;
            $msg = "exceptr/" . $fileName;
        }
    }
    return array($result, $msg);
}

?>