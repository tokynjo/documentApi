<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FOS\UserBundle\Model\User;
use FOS\UserBundle\Command\CreateUserCommand as BaseCommand;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fos:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('nom', InputArgument::REQUIRED, 'Nom'),
                new InputArgument('prenom', InputArgument::REQUIRED, 'Prenom'),
                new InputArgument('telephone', InputArgument::REQUIRED, 'Téléphone'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
            ))
            ->setHelp(<<<'EOT'
The <info>fos:user:create</info> command creates a user:

  <info>php %command.full_name% matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php %command.full_name% matthieu matthieu@example.com mypassword</info>

You can create a super admin via the super-admin flag:

  <info>php %command.full_name% admin --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php %command.full_name% thibault --inactive</info>

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $telephone = $input->getArgument('telephone');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');
        $usermanager = $this->getContainer()->get('fos_user.user_manager');
        $user = $usermanager->createUser();
        $user->setUsername($nom);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((bool)!$inactive);
        $user->setSuperAdmin((bool)$superadmin);
        $user->addRole('ROLE_ADMIN');
        $user->setFirstname($prenom);
        $user->setLastname($nom);
        $user->setPhone($telephone);
        $user->setCreatedIp(getenv('SERVER_ADDR'));
        $usermanager->updateUser($user);
        $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();
        if (!$input->getArgument('nom')) {
            $question = new Question('Votre nom:');
            $question->setValidator(function ($nom) {
                if (empty($nom)) {
                    throw new \Exception('Nom ne peut pas être vide');
                }
                return $nom;
            });
            $questions['nom'] = $question;
        }
        if (!$input->getArgument('prenom')) {
            $question = new Question('Votre prénom:');
            $question->setValidator(function ($prenom) {
                if (empty($prenom)) {
                    throw new \Exception('Prénom ne peut pas être vide');
                }
                return $prenom;
            });
            $questions['prenom'] = $question;
        }
        if (!$input->getArgument('telephone')) {
            $question = new Question('Votre téléphone:');
            $question->setValidator(function ($telephone) {
                if (empty($telephone)) {
                    throw new \Exception('Téléphone ne peut pas être vide');
                }
                return $telephone;
            });
            $questions['telephone'] = $question;
        }
        if (!$input->getArgument('email')) {
            $question = new Question('Votre email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email ne peut pas être vide');
                }

                return $email;
            });
            $questions['email'] = $question;
        }
        if (!$input->getArgument('password')) {
            $question = new Question('Votre mot de pass:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password ne peut pas être vide');
                }
                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }
        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}