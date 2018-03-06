<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:26
 */
namespace AppBundle\Event;

use AppBundle\Entity\Folder;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FolderEvent
 * @package AppBundle\Events
 */
class FolderEvent extends Event
{
    private $folder;

    const FOLDER_ON_LOCK = "folder.on.lock";
    const FOLDER_ON_UNLOCK = "folder.on.unlock";
    const FOLDER_ON_CREATION = "folder.on.creation";
    const FOLDER_ON_RENAME = "folder.on.rename";
    const FOLDER_ON_DELETE = "folder.on.delete";

    /**
     * @param Folder $folder
     */
    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return Folder
     */
    public function getFolder(){
        return $this->folder;
    }
}
