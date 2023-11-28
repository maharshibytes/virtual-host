<?php

# |--------------------------------------------------------------| #
# | Name    : AGVH (Auto Generate Virtual Host)                  | #
# | Version : 1.0                                                | #
# |                                                              | # 
# | Using this script user can create a new                      | #
# | Virtual host within a few seconds                            | #
# |                                                              | #
# | How to Use:                                                  | #
# | Open your terminal and run this index.php file with php      | #
# | EG : php index.php                                           | #
# |                                                              | #
# | After run this script will ask you few basic question        | #
# | that you need to answer which will be regarding virtual host | #
# |                                                              | #
# | Note:                                                        | #
# | This will only work with Apache2 & Ubuntu Linux system       | #
# |                                                              | #
# | Will not work with below server and system :                 | #
# | XAMPP, WAMPP, LAMPP and                                      | #
# | other similar server And Windows system                      | #
# |--------------------------------------------------------------| #

# Adding the required files
require_once ('includes/helper.php');
require_once ('HostClass.php');

setupWizard();

# Asking question for host name
$virtualHostName = getUserHostName();

# Asking question for document (project) root path
$virtualHostDocumentRootDirectory = getDocumentRoot();

# Asking question got adding AllowOverride feature or not
$allowOverride = askQuestion('Do you want add AllowOverride if yes enter (y) to continue :');

# Creating the instance of main HostClass
$host = new HostClass($virtualHostName, $virtualHostDocumentRootDirectory, $allowOverride);

# Final confirmation to create virtual host or not
$isCreate = askQuestion('Enter (y) to create a new virtualhost :');

if (strtolower($isCreate) == 'y') {
    # Creating a new virtual host
    $host->executeCommands();
} else {
    # Exiting the setup wizard
    showInfo('Exiting the setup wizard!!');
    showInfo('Thank you!');
    printNextLine();
    printNextLine();
}
