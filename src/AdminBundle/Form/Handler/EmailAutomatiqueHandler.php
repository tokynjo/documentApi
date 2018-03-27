<?php

namespace AdminBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class EmailAutomatiqueHandler
{
    protected $request;
    protected $form;
    protected $entityManager;

    /**
     * EmailAutomatiqueHandler constructor.
     * @param Form                   $form
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Form $form, Request $request, EntityManagerInterface $entityManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->entityManager->persist($this->form->getData());
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

}
