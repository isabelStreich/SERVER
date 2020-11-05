<?php
// PROVIENT DE MON CODE NBM
public function SetLangue($langue="") {

global $NBM;

// INPUT $langue
// OUTPUT $this->user_langue

if ($langue <> "") {
    if ($langue == "fr") {
        $this->user_langue = "fr";
    } elseif ($langue == "en") {
        $this->user_langue = "en";
    } else {
        $this->user_langue = "en";
        // non pour search engines Warn("Invalid $langue input parameter in PHP function SelLangue.");
    }
} else {

    if(isset($_COOKIE['user_langue'])){
        // la langue de la derniere visite
        $lang= $_COOKIE['user_langue'];
    }else{
        // trouve la langue du browser
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }
    if ($lang == 'fr') {
        $this->user_langue = "fr";
    } elseif ($lang == 'en') {
        $this->user_langue = "en";
    } else {
        $this->user_langue = $NBM['DEFAULT_USER_LANGUE'];
        // devrait forcer la page change_lange.html, voir page prinicipale get("/",... MAIS dans ce cas les robots de search engines mettent
        // change_langue comme page principale du site, donc on force une langue par défaut
    }
}
$this->Save_In_Session();
}
