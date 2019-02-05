<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FixturesCommand extends Command
{
    protected static $defaultName = 'app:fixtures';
    protected $em;
    protected $encoder;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        ?string$name = null)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $faker =\Faker\Factory::create('fr_FR');

        $answer = $io->ask("Truncating all tables... Sure ? [yes/no], no");
        if($answer !== "yes"){
            $io->text("aborttttttting");
            die();
        }

        $conn = $this->em->getConnection();

        $conn->query('SET FOREIGN_KEY_CHECKS = 0');

        $conn->query('TRUNCATE article');
        $conn->query('TRUNCATE user');

        $conn->query('SET FOREIGN_KEY_CHECKS = 1');

        $io->text("Tables are now empty...");

        $io->progressStart(100);

        $roles = [["admin"],["user"]];

        for($i=0;$i<100;$i++){
            $io->progressAdvance(1);

            $user = new User();
            $user->setUsername($faker->name);
            $user->setEmail($faker->email);
            $user->setRoles($faker->randomElements($roles));
            $user->setPassword($this->encoder->encodePassword($user, $user->getUsername()));

            $this->em->persist($user);

            $article = new Article();
            $article->setName($faker->name);
            $article->setDescription($faker->text);
            $article->setPrice($faker->randomNumber());

            $this->em->persist($article);
        }
        $io->progressFinish();

        $this->em->flush();

        $io->success("!Done");
    }
}
