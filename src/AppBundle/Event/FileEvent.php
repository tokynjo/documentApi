<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:26
 */
namespace AppBundle\Event;

use AppBundle\Entity\File;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FileEvent
 *
 * @package AppBundle\Events
 */
class FileEvent extends Event
{
    private $file;

    const FILE_ON_CREATE = "file.on.create";
    const FILE_ON_DELETE = "file.on.delete";
    const FILE_ON_CHANGE_OWNER = "file.on.change_owner";
    const FILE_ON_RENAME = "file.on.rename";
    const FILE_ON_MOVE = "file.on.move";
    const FILE_ON_COPY = "file.on.copy";

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
