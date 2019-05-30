<?php

    if(file_exists('../vendor/autoload.php'))
    {
        require '../vendor/autoload.php';
    } 
    else 
    {
        echo "<p>Install Composer</p>";
        exit;
    }
    define('ENVIRONMENT', 'development');

    if (defined('ENVIRONMENT'))
    {

        if (ENVIRONMENT == 'development')
        {
            error_reporting(E_ALL);
            $devPreloadClasses = new tls\system\DevPreloadClasses();
        }
        elseif (ENVIRONMENT == 'production')
        {
            error_reporting(0);
        }
        else
        {
            exit('Environment incorrect.');
        }

        tls\elevator\controller\Building::main();
    }
?>
