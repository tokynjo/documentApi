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

    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }
    public function getFolder()
    {
        return $this->folder;
    }
}
