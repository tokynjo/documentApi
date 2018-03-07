<?php

namespace AdminBundle\Form;

use AppBundle\Entity\Constants\Constant;
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
            ->add('declenchement', ChoiceType::class, [
                'choices' => array_flip(Constant::$declenchement),
                'translation_domain' => 'messages'])

              ->add('template', TextareaType::class, array('required' => true))
            ->add('etat', ChoiceType::class, [
                'choices' => ["Actif" => 1,
                    "Inactif" => 0]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\EmailAutomatique'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_emailautomatique';
    }

}
