<?php

/**
 * Defining the readline to use this one function if not found in PHP library
 */
if (!function_exists('readline')) {
    function readline($question)
    {
        $fh = fopen('php://stdin', 'r');
        echo $question;
        $userInput = trim(fgets($fh));
        fclose($fh);
        return $userInput;
    }
}

/**
 * Adding the content in the file
 * @param $file    string
 * @param $content string
 * @return boolean
 * 
 */
function hasWrittenFileContent($file, $content, $path)
{
    if ( !is_writable($path) ) {
        shell_exec('sudo sh -c \'echo "'. $content .'" >> ' . $file . '\'');
        return true;
    }
    $fp = fopen($file, 'w');
    fwrite($fp, $content);
    fclose($fp);
    chmod($file, 0777);
    return true;
}

/**
 * Printing the next line
 */
function printNextLine()
{
    echo "\n\r";
}

/**
 * get the next line
 * @return string
 */
function getNextLine()
{
    return "\n\r";
}

/**
 * Printing the data to terminal
 * @param string
 */
function showInfo($data)
{
    echo printNextLine() . $data;
}

/**
 * Ask the question with print to teminal and take the user input
 * @param string
 * @param string|null
 * @return mixed
 */
function askQuestion($data)
{
    printNextLine();
    return trim(readline($data . ' '));
}

/**
 * Printing the setup wizard information on screen
 */
function setupWizard()
{
    showInfo('|-------------------------------------------------------|');
    showInfo('| >>>>> Welcome to setup wizard of "Virtual Host" <<<<< |');
    showInfo('| >>>>>       Setup virtual host easy here        <<<<< |');
    showInfo('| >>>>>   Provide some V Host basic information   <<<<< |');
    showInfo('| >>>>>      And ready to hit the local world     <<<<< |');
    showInfo('|-------------------------------------------------------|');
    printNextLine();
}

/**
 * Asking a question for a virtual host name
 * @return mixed
 */
function getUserHostName()
{
    showInfo('INFORMATION: ');
    showInfo('Set your virtual host like shown below: ');
    showInfo(' #1: local.test.com');
    showInfo(' #2: dev.test.in');
    showInfo(' #3: mylocal.test.in');
    printNextLine();
    return askQuestion('Enter name of your virtual host : ');
}

/**
 * Asking a question for document (project) root directory
 */
function getDocumentRoot()
{
    showInfo('INFORMATION: ');
    showInfo('Your document (project) root directory should be like below:');
    showInfo(' #1: /var/www/html/my-test.com');
    showInfo(' #2: /var/www/laravel-8.com/public');
    showInfo(' #3: /var/www/my-test');
    printNextLine();
    return askQuestion('Enter document (project) root directory: ');
}
