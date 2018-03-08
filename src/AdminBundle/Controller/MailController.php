<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\EmailAutomatique;
use AdminBundle\Form\EmailAutomatiqueType;
use AdminBundle\Form\Handler\EmailAutomatiqueHandler;
use AppBundle\Entity\Constants\Constant;
use AppBundle\Manager\EmailAutomatiqueManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/mail-automatique")
 */
class MailController extends Controller
{
    /**
     * add/edit
     *
     * @Method({"GET", "POST"})
     * @Route("/add", name="admin_mail_auto_add")
     * @Route("/edit/{id}", name="admin_mail_auto_edit")
     */
    public function addAction(Request $request)
    {
        $id = $request->get('id');
        if ($id) {
            if (!$pm = $this->get(EmailAutomatiqueManager::SERVICE_NAME)->findOneBy(
                ['id' => $id, 'deletedAt' => null]
            )
            ) {
                throw $this->createNotFoundException(
                    $this->get('translator')->trans('page.not_found', [], 'label', 'fr')
                );
            }
        } else {
            $pm = new EmailAutomatique();
        }
        $form = $this->createForm(EmailAutomatiqueType::class, $pm);
        $formHandler = new EmailAutomatiqueHandler($form, $request, $this->getDoctrine()->getManager());
        if ($formHandler->process()) {
            $this->get('session')->getFlashBag()->add('infos', 'Le mail est enregistré.');
            return $this->redirectToRoute("admin_headerfooter_list");
        }
        return $this->render('admin/add.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * list header footer mail
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"GET"})
     * @Route("/", name="admin_headerfooter_list")
     */
    public function indexAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $mails = $entityManager->getRepository("AdminBundle:EmailAutomatique")->findBy(['deletedAt' => null]);
        return $this->render('admin/mail_automatique.html.twig', array(
            'headerFooter' => $mails,
            'tab_declenchement' =>Constant::$declenchement
        ));
    }

    /**
     * @param Request $request
     * @return Response
     * @Method("GET")
     * @Route("/delete/{id}", name="delete_email_automatique")
     */
    public function supprAction(Request $request, $id)
    {
        $manager = $this->get(EmailAutomatiqueManager::SERVICE_NAME);
        $email = $manager->find($id);
        $email->setDeletedAt(new \DateTime());
        $manager->saveAndFlush($email);
        return $this->redirectToRoute("admin_headerfooter_list");
    }

}
