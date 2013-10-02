<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danny
 * Date: 01/10/2013
 * Time: 13:45
 * To change this template use File | Settings | File Templates.
 */

namespace core\task\googledrive;

{
    abstract class GoogleDriveFolderTask extends GoogleDriveTask {

        protected $folderId;
        protected $outputFolder;

        public function __construct($client,$folderId,$outputFolder){
            parent::__construct($client);
            $this->folderId = $folderId;
            $this->outputFolder = $outputFolder;
        }



    }
}