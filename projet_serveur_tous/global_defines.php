<?php

if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Direct access to this php file is forbidden');
}

// info compagnie
define('COMPANY_NAME', 'ClassicModels.com');
define('COMPANY_STREET_ADDRESS', '5340 St-Laurent');
define('COMPANY_CITY', 'Montréal');
define('COMPANY_PROVINCE', 'QC');
define('COMPANY_COUNTRY', 'Canada');
define('COMPANY_POSTAL_CODE', 'J0P 1T0');
define('COMPANY_TEL', '514-123-4567');
define('COMPANY_EMAIL', 'info@scooterelectrique.com');

// page web valeurs par défauts, voir webpage.php
define('DEFAULT_PAGE_TITLE', 'ClassicModels.com');
define('DEFAULT_PAGE_DESCRIPTION', 'Le plus vaste de choix de scooters électriques à Montréal - Vente - Service - Pièces');
define('DEFAULT_PAGE_AUTHOR', 'Stéphane Lapointe');
define('WEB_SITE_ICON_FILE', 'web_site_icon.jpg');
