<?php
namespace AppBundle\Event;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CommentEvent
 * @package AppBundle\Events
 */
class CommentEvent extends Event
{
    private $comment;
    private $toNotify = null;

    const COMMENT_ON_CREATE = "comment.on.create";


    /**
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return File|Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return null
     */
    public function getToNotify()
    {
        return $this->toNotify;
    }

    /**
     * @param null $toNotify
     * @return $this
     */
    public function setToNotify($toNotify)
    {
        $this->toNotify = $toNotify;
        return $this;
    }
}
