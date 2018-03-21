<?php

namespace AdminBundle\Form;

use ApiBundle\Entity\User;
use ApiBundle\Manager\UserManager;
use AppBundle\Entity\Constants\Constant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailAutomatiqueType extends AbstractType
{
    protected $user = null;

    /**
     * EmailAutomatiqueType constructor.
     */
    public function __construct()
    {
        $this->user = $GLOBALS['kernel']->getContainer()->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('objet')
            ->add('declenchement', ChoiceType::class, ['choices' => array_flip(Constant::$declenchement), 'translation_domain' => 'messages'])
            ->add('emitter', EntityType::class, array(
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'email'
            ))
            ->add('template', TextareaType::class, array('required' => true))
            ->add('etat', ChoiceType::class, ['choices' => ["Actif" => 1, "Inactif" => 0]]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AdminBundle\Entity\EmailAutomatique'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_emailautomatique';
    }

}
