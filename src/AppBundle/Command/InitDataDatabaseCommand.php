<?php
namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\ClientType;
use AppBundle\Entity\NewsType;
use AppBundle\Entity\Right;
use AppBundle\Entity\Role;
use AppBundle\Entity\View;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitDataDatabaseCommand extends ContainerAwareCommand
{
    private $em = null;

    /**
     * Class InitDataDatabaseCommand
     * init set constants data values in the databases
     * @package AppBundle\Command
     */
    protected function configure()
    {
        $this
            ->setName('wedrop:data:init')
            ->setDescription('Initialize data into database')
            ->addOption(
                'table',
                't',
                InputOption::VALUE_REQUIRED,
                'init selected table data'
            )
            ->setHelp(<<<'EOT'
            The <info>wedrop:data:init</info> initialize and insert constant data into database
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        switch ($input->getOption('table')) {
            case 'category':
                $this->initCategory();
                break;
            case 'right':
                $this->initRight();
                break;
            case 'role':
                $this->initRoles();
                break;
            case 'view':
                $this->initView();
                break;
            case 'client-type':
                $this->initClientType();
                break;
            case 'news-type':
                $this->initNewsType();
                break;
            case 'country':
                $this->initCountry();
                break;
            case 'all':
                $this->initAll();
                break;
        }
    }

    /**
     * init all data
     */
    protected function initAll()
    {
        $this->initCategory();
        $this->initRight();
        $this->initRoles();
        $this->initView();
        $this->initClientType();
        $this->initNewsType();
        $this->initCountry();
    }

    /**
     * init category
     * @return void
     */
    protected function initCategory()
    {
        printf(" Init categories constants...");
        $c = new Category();
        $c->setId('1')->setLabel('Client simple');
        $this->em->persist($c);
        $c = new Category();
        $c->setId('2')->setLabel('revendeur');
        $this->em->persist($c);
        $this->em->flush();
        printf(" OK.\n");
    }

    /**
     * create right definition in database
     * @return void
     */
    protected function initRight()
    {
        printf(" Init Rights constants...");
        $c = new Right();
        $c->setId('1')->setName('Manager');
        $this->em->persist($c);
        $c = new Right();
        $c->setId('2')->setName('Contributor');
        $this->em->persist($c);
        $c = new Right();
        $c->setId('3')->setName('Reader');
        $this->em->persist($c);
        $c = new Right();
        $c->setId('4')->setName('Owner');
        $this->em->persist($c);
        $this->em->flush();
        printf(" OK.\n");
    }

    /**
     * create roles definitions in database
     * @return void
     */
    protected function initRoles()
    {
        printf(" Init Roles constants...");
        $c = new Role();
        $c->setId('1')->setLabel('Super admin')->setDescription(null);
        $this->em->persist($c);
        $c = new Role();
        $c->setId('2')->setLabel('Administrateur')->setDescription(null);
        $this->em->persist($c);
        $c = new Role();
        $c->setId('3')->setLabel('Utilisateur')->setDescription(null);
        $this->em->persist($c);
        $c = new Role();
        $c->setId('4')->setLabel('Administrateur entité')->setDescription(null);
        $this->em->persist($c);

        $this->em->flush();
        printf(" OK.\n");
    }

    /**
     * create view definitions in database
     * @return void
     */
    protected function initView()
    {
        printf(" Init vue constants...");
        $c = new View();
        $c->setId('1')->setLabel('Trier par repertoire');
        $this->em->persist($c);
        $c = new View();
        $c->setId('2')->setLabel('Trier par unité');
        $this->em->persist($c);
        $c = new View();
        $c->setId('3')->setLabel('Trier par projet');
        $this->em->persist($c);
        $c = new View();
        $c->setId('4')->setLabel('Trier par propriétraire');
        $this->em->persist($c);

        $this->em->flush();
        printf(" OK\n");
    }

    /**
     * create client type definitions in database
     * @return void
     */
    protected function initClientType()
    {
        printf(" Init Client type constants...");
        $c = new ClientType();
        $c->setId('1')->setLabel('Administration')->setType('AD');
        $this->em->persist($c);
        $c = new ClientType();
        $c->setId('2')->setLabel('Association')->setType('AS');
        $this->em->persist($c);
        $c = new ClientType();
        $c->setId('3')->setLabel('Entreprise')->setType('EN');
        $this->em->persist($c);
        $c = new ClientType();
        $c->setId('4')->setLabel('Particulier')->setType('PA');
        $this->em->persist($c);

        $this->em->flush();
        printf(" OK.\n");
    }

    /**
     * create client type definitions in database
     * @return void
     */
    protected function initNewsType()
    {
        printf(" Init News type constants ...");
        $c = new NewsType();
        $c->setId('1')->setLabel('Commentaire');
        $this->em->persist($c);
        $c = new NewsType();
        $c->setId('2')->setLabel('Ajout d\'utilisateur');
        $this->em->persist($c);
        $c = new NewsType();
        $c->setId('3')->setLabel('Upload fichier');
        $this->em->persist($c);
        $c = new NewsType();
        $c->setId('4')->setLabel('Creation dossier');
        $this->em->persist($c);
        $c = new NewsType();
        $c->setId('5')->setLabel('Creation projet');
        $this->em->persist($c);
        $c = new NewsType();
        $c->setId('6')->setLabel('Mettre en avant un commentaire');
        $this->em->persist($c);

        $this->em->flush();
        printf(" OK.\n");
    }

    protected function initCountry()
    {
        printf(" Init Country constants ...");
        $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        INSERT INTO `my_country` (`id`, `label`, `ue`, `id_currency`, `country_phone_code`, `lang`, `display_flag`, `payment_card`, `vat_rate`) VALUES
        ('AL', 'Albanie', 0, '978', 355, 'en', 'N', 'O', 1),
        ('DZ', 'Algerie', 0, '978', 213, 'en', 'N', 'O', 1),
        ('AD', 'Andorre', 0, '978', 376, 'en', 'N', 'O', 1),
        ('AO', 'Angola', 0, '978', 244, 'en', 'N', 'O', 1),
        ('AI', 'Anguilla', 0, '978', 1264, 'en', 'N', 'O', 1),
        ('AQ', 'Antartica', 0, '978', 0, 'en', 'N', 'O', 1),
        ('AG', 'Antigua and Barbuda', 0, '978', 1268, 'en', 'N', 'O', 1),
        ('AR', 'Argentina', 0, '978', 54, 'en', 'N', 'O', 1),
        ('AM', 'Armenia', 0, '978', 374, 'en', 'N', 'O', 1),
        ('AW', 'Aruba', 0, '978', 97, 'en', 'N', 'O', 1),
        ('AC', 'Ascension Island', 0, '978', 247, 'en', 'N', 'O', 1),
        ('AU', 'Australie', 0, 'AUD', 61, 'en', 'O', 'O', 1),
        ('AT', 'Autriche', 1, '978', 43, 'en', 'N', 'O', 1.2),
        ('AZ', 'Azerbaijan', 0, '978', 994, 'en', 'N', 'O', 1),
        ('BS', 'Bahamas', 0, '978', 1242, 'en', 'N', 'O', 1),
        ('BH', 'Bahrain', 0, '978', 973, 'en', 'N', 'O', 1),
        ('BD', 'Bangladesh', 0, '978', 880, 'en', 'N', 'O', 1),
        ('BB', 'Barbados', 0, '978', 1246, 'en', 'N', 'O', 1),
        ('BY', 'Belarus', 0, '978', 375, 'en', 'N', 'O', 1),
        ('BE', 'Belgique', 1, '978', 32, 'fr', 'N', 'O', 1.21),
        ('BZ', 'Belize', 0, '978', 501, 'en', 'N', 'O', 1),
        ('BJ', 'Benin', 0, '978', 229, 'en', 'N', 'O', 1),
        ('BM', 'Bermuda', 0, '978', 1441, 'en', 'N', 'O', 1),
        ('BT', 'Bhutan', 0, '978', 975, 'en', 'N', 'O', 1),
        ('BO', 'Bolivia', 0, '978', 591, 'en', 'N', 'O', 1),
        ('BA', 'Bosnia and Herzegovina', 0, '978', 387, 'en', 'N', 'O', 1),
        ('BW', 'Botswana', 0, '978', 267, 'en', 'N', 'O', 1),
        ('BR', 'Brazil', 0, '978', 55, 'en', 'N', 'O', 1),
        ('BN', 'Brunei Darussalam', 0, '978', 673, 'en', 'N', 'O', 1),
        ('BG', 'Bulgaria', 0, '978', 359, 'en', 'N', 'O', 1),
        ('BF', 'Burkina Faso', 0, '978', 226, 'fr', 'N', 'O', 1),
        ('BI', 'Burundi', 0, '978', 257, 'en', 'N', 'O', 1),
        ('KH', 'Cambodia', 0, '978', 855, 'en', 'N', 'O', 1),
        ('CM', 'Cameroon', 0, '978', 237, 'fr', 'N', 'O', 1),
        ('CA', 'Canada', 0, 'CAD', 1, 'fr', 'N', 'O', 1),
        ('CV', 'Cap Verde', 0, '978', 238, 'en', 'N', 'O', 1),
        ('KY', 'Cayman Islands', 0, '978', 1345, 'en', 'N', 'O', 1),
        ('CF', 'Central African Republic', 0, '978', 236, 'fr', 'N', 'O', 1),
        ('CL', 'Chile', 0, '978', 56, 'en', 'N', 'O', 1),
        ('CN', 'China', 0, '978', 86, 'en', 'N', 'O', 1),
        ('CO', 'Colombia', 0, '978', 57, 'en', 'N', 'O', 1),
        ('KM', 'Comoros', 0, '978', 269, 'en', 'N', 'O', 1),
        ('CG', 'Congo, Republic of', 0, '978', 243, 'en', 'N', 'O', 1),
        ('CK', 'Cook Islands', 0, '978', 682, 'en', 'N', 'O', 1),
        ('CR', 'Costa Rica', 0, '978', 506, 'en', 'N', 'O', 1),
        ('CI', 'Cote d''Ivoire', 0, '978', 225, 'fr', 'N', 'O', 1),
        ('HR', 'Croatia/Hrvatska', 0, '978', 385, 'en', 'N', 'O', 1),
        ('CU', 'Cuba', 0, '978', 53, 'en', 'N', 'O', 1),
        ('CY', 'Cyprus', 1, '978', 357, 'en', 'N', 'O', 1.19),
        ('CZ', 'Czech Republic', 1, '978', 420, 'en', 'N', 'O', 1.21),
        ('DK', 'Denmark', 1, '978', 45, 'en', 'N', 'O', 1.25),
        ('DJ', 'Djibouti', 0, '978', 253, 'en', 'N', 'O', 1),
        ('DM', 'Dominica', 0, '978', 1767, 'en', 'N', 'O', 1),
        ('DO', 'Dominican Republic', 0, '978', 1809, 'en', 'N', 'O', 1),
        ('TP', 'East Timor', 0, '978', 0, 'en', 'N', 'O', 1),
        ('EC', 'Ecuador', 0, '978', 593, 'en', 'N', 'O', 1),
        ('EG', 'Egypt', 0, '978', 20, 'en', 'N', 'O', 1),
        ('SV', 'El Salvador', 0, '978', 503, 'en', 'N', 'O', 1),
        ('GQ', 'Equatorial Guinea', 0, '978', 240, 'en', 'N', 'O', 1),
        ('ER', 'Eritrea', 0, '978', 291, 'en', 'N', 'O', 1),
        ('EE', 'Estonia', 1, '978', 372, 'en', 'N', 'O', 1.2),
        ('ET', 'Ethiopia', 0, '978', 251, 'en', 'N', 'O', 1),
        ('FK', 'Falkland Islands (Malvina)', 0, '978', 0, 'en', 'N', 'O', 1),
        ('FO', 'Faroe Islands', 0, '978', 298, 'en', 'N', 'O', 1),
        ('FJ', 'Fiji', 0, '978', 679, 'en', 'N', 'O', 1),
        ('FI', 'Finland', 1, '978', 358, 'en', 'N', 'O', 1.24),
        ('FR', 'France', 1, '978', 33, 'fr', 'O', 'O', 1.2),
        ('TOM', 'France-TOM', 1, '978', 33, 'fr', 'N', 'O', 1),
        ('DOM', 'France-DOM', 1, '978', 33, 'fr', 'N', 'O', 1.085),
        ('GA', 'Gabon', 0, '978', 241, 'en', 'N', 'O', 1),
        ('GM', 'Gambia', 0, '978', 220, 'en', 'N', 'O', 1),
        ('GE', 'Georgia', 0, '978', 995, 'en', 'N', 'O', 1),
        ('DE', 'Germany', 1, '978', 49, 'de', 'N', 'O', 1.19),
        ('GH', 'Ghana', 0, '978', 233, 'en', 'N', 'O', 1),
        ('GI', 'Gibraltar', 0, '978', 350, 'en', 'N', 'O', 1),
        ('GR', 'Greece', 1, '978', 30, 'en', 'N', 'O', 1.24),
        ('GL', 'Greenland', 0, '978', 299, 'en', 'N', 'O', 1),
        ('GD', 'Grenada', 0, '978', 1473, 'en', 'N', 'O', 1),
        ('GU', 'Guam', 0, '978', 671, 'en', 'N', 'O', 1),
        ('GT', 'Guatemala', 0, '978', 502, 'en', 'N', 'O', 1),
        ('GG', 'Guernsey', 0, '978', 0, 'en', 'N', 'O', 1),
        ('GN', 'Guinea', 0, '978', 224, 'en', 'N', 'O', 1),
        ('GW', 'Guinea-Bissau', 0, '978', 245, 'en', 'N', 'O', 1),
        ('GY', 'Guyane fran&ccedil;aise', 0, '978', 594, 'fr', 'N', 'O', 1),
        ('HT', 'Haiti', 0, '978', 509, 'en', 'N', 'O', 1),
        ('HM', 'Heard and McDonald Islands', 0, '978', 0, 'en', 'N', 'O', 1),
        ('VA', 'Holy See (City Vatican State)', 0, '978', 0, 'en', 'N', 'O', 1),
        ('HN', 'Honduras', 0, '978', 504, 'en', 'N', 'O', 1),
        ('HK', 'Hong Kong', 0, '978', 852, 'en', 'N', 'O', 1),
        ('HU', 'Hungary', 1, '978', 36, 'en', 'N', 'O', 1.23),
        ('IS', 'Iceland', 0, '978', 354, 'en', 'N', 'O', 1),
        ('IN', 'India', 0, '978', 91, 'en', 'N', 'O', 1),
        ('ID', 'Indonesia', 0, '978', 62, 'en', 'N', 'O', 1),
        ('IR', 'Iran (Islamic Republic of)', 0, '978', 98, 'en', 'N', 'O', 1),
        ('IQ', 'Iraq', 0, '978', 964, 'en', 'N', 'O', 1),
        ('IE', 'Ireland', 1, '978', 353, 'en', 'N', 'O', 1.23),
        ('IL', 'Israel', 0, '978', 972, 'fr', 'N', 'O', 1),
        ('IT', 'Italy', 1, '978', 390, 'en', 'N', 'O', 1.22),
        ('JM', 'Jamaica', 0, '978', 1876, 'en', 'N', 'O', 1),
        ('JP', 'Japan', 0, '978', 81, 'en', 'N', 'O', 1),
        ('JO', 'Jordan', 0, '978', 962, 'en', 'N', 'O', 1),
        ('KZ', 'Kazakhstan', 0, '978', 7, 'en', 'N', 'O', 1),
        ('KE', 'Kenya', 0, '978', 254, 'en', 'N', 'O', 1),
        ('KI', 'Kiribati', 0, '978', 686, 'en', 'N', 'O', 1),
        ('KR', 'Korea, Republic of', 0, '978', 82, 'en', 'N', 'O', 1),
        ('KW', 'Kuwait', 0, '978', 965, 'en', 'N', 'O', 1),
        ('KG', 'Kyrgyzstan', 0, '978', 996, 'en', 'N', 'O', 1),
        ('LA', 'Lao People''s Democratic Republic', 0, '978', 856, 'en', 'N', 'O', 1),
        ('LV', 'Latvia', 1, '978', 371, 'en', 'N', 'O', 1.21),
        ('LB', 'Lebanon', 0, '978', 961, 'en', 'N', 'O', 1),
        ('LS', 'Lesotho', 0, '978', 266, 'en', 'N', 'O', 1),
        ('LR', 'Liberia', 0, '978', 231, 'en', 'N', 'O', 1),
        ('LY', 'Libyan Arab Jamahiriya', 0, '978', 218, 'en', 'N', 'O', 1),
        ('LI', 'Liechtenstein', 0, '978', 423, 'en', 'N', 'O', 1),
        ('LT', 'Lithuania', 1, '978', 370, 'en', 'N', 'O', 1.21),
        ('LU', 'Luxembourg', 1, '978', 352, 'fr', 'N', 'O', 1.17),
        ('MO', 'Macau', 0, '978', 853, 'en', 'N', 'O', 1),
        ('MK', 'Macedonia', 0, '978', 389, 'en', 'N', 'O', 1),
        ('MG', 'Madagascar', 0, '978', 261, 'en', 'N', 'O', 1),
        ('MW', 'Malawi', 0, '978', 265, 'en', 'N', 'O', 1),
        ('MY', 'Malaysia', 0, '978', 60, 'en', 'N', 'O', 1),
        ('MV', 'Maldives', 0, '978', 960, 'en', 'N', 'O', 1),
        ('ML', 'Mali', 0, '978', 223, 'en', 'N', 'O', 1),
        ('MT', 'Malta', 1, '978', 356, 'en', 'N', 'O', 1.18),
        ('MH', 'Marshall Islands', 0, '978', 692, 'en', 'N', 'O', 1),
        ('MQ', 'Martinique', 0, '978', 33, 'fr', 'N', 'O', 1),
        ('MR', 'Mauritania', 0, '978', 222, 'en', 'N', 'O', 1),
        ('MU', 'Mauritius', 0, '978', 230, 'en', 'N', 'O', 1),
        ('YT', 'Mayotte', 0, '978', 0, 'en', 'N', 'O', 1),
        ('MX', 'Mexico', 0, '978', 52, 'en', 'N', 'O', 1),
        ('MD', 'Moldova, Republic of', 0, '978', 373, 'en', 'N', 'O', 1),
        ('MC', 'Monaco', 0, '978', 377, 'fr', 'N', 'O', 1),
        ('MN', 'Mongolia', 0, '978', 976, 'en', 'N', 'O', 1),
        ('MS', 'Montserrat', 0, '978', 1664, 'en', 'N', 'O', 1),
        ('MA', 'Morocco', 0, '978', 212, 'fr', 'N', 'O', 1),
        ('MZ', 'Mozambique', 0, '978', 258, 'en', 'N', 'O', 1),
        ('MM', 'Myanmar', 0, '978', 95, 'en', 'N', 'O', 1),
        ('NA', 'Namibia', 0, '978', 264, 'en', 'N', 'O', 1),
        ('NR', 'Nauru', 0, '978', 674, 'en', 'N', 'O', 1),
        ('NP', 'Nepal', 0, '978', 977, 'en', 'N', 'O', 1),
        ('NL', 'Netherlands', 1, '978', 31, 'en', 'N', 'O', 1.21),
        ('AN', 'Netherlands Antilles', 0, '978', 599, 'en', 'N', 'O', 1),
        ('NZ', 'New Zealand', 0, '978', 64, 'en', 'N', 'O', 1),
        ('NI', 'Nicaragua', 0, '978', 505, 'en', 'N', 'O', 1),
        ('NE', 'Niger', 0, '978', 227, 'en', 'N', 'O', 1),
        ('NG', 'Nigeria', 0, '978', 234, 'en', 'N', 'O', 1),
        ('NU', 'Niue', 0, '978', 683, 'en', 'N', 'O', 1),
        ('NF', 'Norfolk Island', 0, '978', 0, 'en', 'N', 'O', 1),
        ('MP', 'Northern Mariana Islands', 0, '978', 0, 'en', 'N', 'O', 1),
        ('NO', 'Norway', 0, '978', 47, 'en', 'N', 'O', 1),
        ('OM', 'Oman', 0, '978', 968, 'en', 'N', 'O', 1),
        ('PK', 'Pakistan', 0, '978', 92, 'en', 'N', 'O', 1),
        ('PW', 'Palau', 0, '978', 680, 'en', 'N', 'O', 1),
        ('PS', 'Palestinian Territories', 0, '978', 970, 'en', 'N', 'O', 1),
        ('PA', 'Panama', 0, '978', 507, 'en', 'N', 'O', 1),
        ('PG', 'Papua New Guinea', 0, '978', 0, 'en', 'N', 'O', 1),
        ('PY', 'Paraguay', 0, '978', 595, 'en', 'N', 'O', 1),
        ('PE', 'Peru', 0, '978', 51, 'en', 'N', 'O', 1),
        ('PH', 'Philippines', 0, '978', 63, 'en', 'N', 'O', 1),
        ('PN', 'Pitcairn Island', 0, '978', 0, 'en', 'N', 'O', 1),
        ('PL', 'Poland', 1, '978', 48, 'en', 'N', 'O', 1.23),
        ('PT', 'Portugal', 1, '978', 351, 'en', 'N', 'O', 1.23),
        ('PR', 'Porto Rico', 0, '978', 1787, 'en', 'N', 'O', 1),
        ('QA', 'Qatar', 0, '978', 974, 'en', 'N', 'O', 1),
        ('RO', 'Romania', 0, '978', 40, 'en', 'N', 'O', 1),
        ('RU', 'Russian Federation', 0, '978', 7, 'fr', 'N', 'O', 1),
        ('RW', 'Rwanda', 0, '978', 250, 'en', 'N', 'O', 1),
        ('KN', 'Saint Kitts and Nevis', 0, '978', 0, 'en', 'N', 'O', 1),
        ('LC', 'Saint Lucia', 0, '978', 1758, 'en', 'N', 'O', 1),
        ('VC', 'Saint Vincent and the Grenadines', 0, '978', 1784, 'en', 'N', 'O', 1),
        ('SM', 'San Marino', 0, '978', 0, 'en', 'N', 'O', 1),
        ('ST', 'Sao Tome and Principe', 0, '978', 0, 'en', 'N', 'O', 1),
        ('SA', 'Saudi Arabia', 0, '978', 966, 'en', 'N', 'O', 1),
        ('SN', 'Senegal', 0, '978', 221, 'fr', 'N', 'O', 1),
        ('YU', 'Serbie - Montenegro', 0, '978', 381, 'en', 'N', 'O', 1),
        ('SC', 'Seychelles', 0, '978', 248, 'en', 'N', 'O', 1),
        ('SL', 'Sierra Leone', 0, '978', 0, 'en', 'N', 'O', 1),
        ('SG', 'Singapore', 0, '978', 65, 'en', 'N', 'O', 1),
        ('SK', 'Slovak Republic', 1, '978', 421, 'en', 'N', 'O', 1.2),
        ('SI', 'Slovenia', 1, '978', 386, 'en', 'N', 'O', 1.22),
        ('SB', 'Solomon Islands', 0, '978', 0, 'en', 'N', 'O', 1),
        ('SO', 'Somalia', 0, '978', 252, 'en', 'N', 'O', 1),
        ('ZA', 'South Africa', 0, '978', 27, 'en', 'N', 'O', 1),
        ('ES', 'Spain', 1, '978', 34, 'es', 'N', 'O', 1.21),
        ('LK', 'Sri Lanka', 0, '978', 94, 'en', 'N', 'O', 1),
        ('SH', 'St-Helena', 0, '978', 290, 'en', 'N', 'O', 1),
        ('PM', 'St-Pierre and Miquelon', 0, '978', 0, 'en', 'N', 'O', 1),
        ('SD', 'Sudan', 0, '978', 249, 'en', 'N', 'O', 1),
        ('SR', 'Suriname', 0, '978', 597, 'en', 'N', 'O', 1),
        ('SZ', 'Swaziland', 0, '978', 268, 'en', 'N', 'O', 1),
        ('SE', 'Sweden', 1, '978', 46, 'en', 'N', 'O', 1.25),
        ('CH', 'Suisse', 0, '978', 41, 'fr', 'O', 'O', 1),
        ('SY', 'Syrian Arab Republic', 0, '978', 963, 'en', 'N', 'O', 1),
        ('TW', 'Taiwan', 0, '978', 886, 'en', 'N', 'O', 1),
        ('TJ', 'Tajikistan', 0, '978', 992, 'en', 'N', 'O', 1),
        ('TZ', 'Tanzania', 0, '978', 255, 'en', 'N', 'O', 1),
        ('TH', 'Thailand', 0, '978', 66, 'fr', 'N', 'O', 1),
        ('TG', 'Togo', 0, '978', 228, 'en', 'N', 'O', 1),
        ('TK', 'Tokelau', 0, '978', 0, 'en', 'N', 'O', 1),
        ('TO', 'Tonga', 0, '978', 676, 'en', 'N', 'O', 1),
        ('TT', 'Trinidad and Tobago', 0, '978', 1868, 'en', 'N', 'O', 1),
        ('TN', 'Tunisia', 0, '978', 216, 'fr', 'N', 'O', 1),
        ('TR', 'Turkey', 0, '978', 90, 'en', 'N', 'O', 1),
        ('TM', 'Turkmenistan', 0, '978', 993, 'en', 'N', 'O', 1),
        ('TC', 'Turks and Caicos Islands', 0, '978', 1649, 'en', 'N', 'O', 1),
        ('TV', 'Tuvalu', 0, '978', 688, 'en', 'N', 'O', 1),
        ('UM', 'US Minor Outlying Islands', 0, '978', 0, 'en', 'N', 'O', 1),
        ('UG', 'Uganda', 0, '978', 256, 'en', 'N', 'O', 1),
        ('UA', 'Ukraine', 0, '978', 380, 'en', 'N', 'O', 1),
        ('AE', 'United Arab Emirates', 0, '978', 971, 'en', 'N', 'O', 1),
        ('UK', 'United Kingdom', 1, '978', 44, 'en', 'O', 'O', 1.2),
        ('US', 'United States', 0, '840', 1, 'en', 'O', 'O', 1),
        ('UY', 'Uruguay', 0, '978', 598, 'en', 'N', 'O', 1),
        ('UZ', 'Uzbekistan', 0, '978', 7, 'en', 'N', 'O', 1),
        ('VU', 'Vanuatu', 0, '978', 0, 'en', 'N', 'O', 1),
        ('VE', 'Venezuela', 0, '978', 58, 'en', 'N', 'O', 1),
        ('VN', 'Vietnam', 0, '978', 84, 'en', 'N', 'O', 1),
        ('VG', 'Virgin Islands (British)', 0, '978', 1284, 'en', 'N', 'O', 1),
        ('VI', 'Virgin Islands (USA)', 0, '978', 1340, 'en', 'N', 'O', 1),
        ('WF', 'Wallis and Futuna Islands', 0, '978', 0, 'en', 'N', 'O', 1),
        ('EH', 'Western Sahara', 0, '978', 0, 'en', 'N', 'O', 1),
        ('WS', 'Western Samoa', 0, '978', 0, 'en', 'N', 'O', 1),
        ('YE', 'Yemen', 0, '978', 967, 'en', 'N', 'O', 1),
        ('ZM', 'Zambia', 0, '978', 260, 'en', 'N', 'O', 1),
        ('ZW', 'Zimbabwe', 0, '978', 263, 'en', 'N', 'O', 1),
        ('TD', 'Tchad', 0, '978', 235, 'fr', 'N', 'N', 1),
        ('GF', 'France-Guyane', 0, '978', 33, 'fr', 'N', 'O', 1);
        SET FOREIGN_KEY_CHECKS = 1
        ";
        $this->em->getConnection()->exec($sql);
        printf(" OK \n");
    }
}
