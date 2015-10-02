<?php
require('BaseSite.php');
require('DrivenowSite.php');
require('ApCardSite.php');
require('DriveJDBSite.php');
require('DriveCNACSite.php');
require('DriveTodayCardSite.php');

class Router {
    public $sites = [];

    function __construct()
    {
        $this->sites[] = new DrivenowSite();
        $this->sites[] = new ApCardSite();
        $this->sites[] = new DriveJDBSite();
        $this->sites[] = new DriveCNACSite();
        $this->sites[] = new DriveTodayCardSite();
    }

    public function getSite($domain)
    {
        foreach($this->sites as $site) {
            if($site->matches($domain))
                return $site;
        }

        return false;
    }
}
?>
