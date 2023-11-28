<?php

/**
 * Class main role is to create a new virtual host
 * And so the sudo related things which is required to create V-Host.
 */
class HostClass
{
    private $name                   = '';
    private $documentRoot           = '';
    private $allowOverride          = '';
    private $localIp                = '127.0.0.1';
    private $extension              = '.conf';
    private $hostsPath              = '/etc/hosts';
    private $vhostAvailableSitePath = '/etc/apache2/sites-available/';

    /**
     * Create instance of the class
     * Set the intials level data
     * @param $name          string|mixed
     * @param $documentRoot  string
     * @param $allowOverride string
     */
    public function __construct($name, $documentRoot, $allowOverride)
    {
        $this->name          = $name;
        $this->documentRoot  = $documentRoot;
        $this->allowOverride = $allowOverride;
    }

    /**
     * Main function from where executing the all dependency
     */
    public function executeCommands()
    {
        $isVhostCreated = $this->createVirtualHostFile();
        if (!$isVhostCreated) {
            showInfo('Something went wrong' . printNextLine());
            showInfo('Exiting the wizard.' . printNextLine());
            printNextLine();
            exit();
        }
        $this->addNewHostToHostsFile();
        showInfo(' [ ' . $this->name . $this->extension . ' ] >>> Virtual host file is created successfully' . printNextLine());
        showInfo(' [ ' . $this->name . ' ] >>> Added to the HOSTS file successfully' . printNextLine());
        $this->enableNewVirtualHost();
        printNextLine();
        showInfo(' New host ready to rock Goto >>>> http://' . $this->name);
        printNextLine();
        printNextLine();
    }

    /**
     * Prepare the content for virtual host file
     * @return string
     */
    private function createVirtualHostFile()
    {
        $virtualHostFileUrl = $this->vhostAvailableSitePath .  $this->name . $this->extension;
        $fileContent = $this->getHostContent();
        return hasWrittenFileContent($virtualHostFileUrl, $fileContent, $this->vhostAvailableSitePath);
    }

    /**
     * Adding the vistual host entry in HOSTS file
     */
    private function addNewHostToHostsFile()
    {
        shell_exec('sudo sh -c \'echo "\n\r" >> '. $this->hostsPath .'\'');
        shell_exec('sudo sh -c \'echo "'. $this->localIp .' '. $this->name .'" >> '. $this->hostsPath .'\'');
    }

    /**
     * Enabling apache2 site
     * Reload apache2 server
     */
    private function enableNewVirtualHost()
    {
        shell_exec("sudo a2ensite $this->name" . $this->extension);
        showInfo(' [ ' . $this->name . $this->extension . ' ] >>> Site enabled successfully' . printNextLine());
        if ( strtolower($this->allowOverride) == 'y' ) {
			shell_exec("sudo a2enmod rewrite");
        }
        shell_exec("sudo systemctl reload apache2");
        showInfo(' Apache2 server restarted successfully' . printNextLine());
    }

    /**
     * Get the file content for the virtual host conf file.
     * @return string
     */
    private function getHostContent()
    {
        # Getting content from files
        $main   = file_get_contents('virtual-host-files/main.conf');
        $server = file_get_contents('virtual-host-files/server.conf');
        $allowOverride = (strtolower($this->allowOverride) == 'y') ? file_get_contents('virtual-host-files/allow-override.conf') : '';

        # Content for the virtual host file (.conf)
        $replacableContent = $server .  $allowOverride;

        # Array which need to replace
        $replacableContentArray = [
            'host_name' => '##_HOST_NAME_##',
            'document_root' => '##_DOCUMENT_ROOT_##',
        ];

        # Array from get the values and repalce with matched data
        $replacableData = [
            'host_name' => $this->name,
            'document_root' => $this->documentRoot,
        ];

        $search  = [];
        $replace = [];
        foreach ($replacableContentArray AS $fieldName => $contentKey) {
            $search[]  = $contentKey;
            $replace[] = (isset($replacableData[$fieldName]) && !empty($replacableData[$fieldName])) ? $replacableData[$fieldName] : '';
        }
        $content = str_replace($search, $replace, $replacableContent);

        return str_replace('##_MAIN_CONTENT_##', $content, $main);
    }
}
