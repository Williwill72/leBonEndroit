<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Category;
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
        $conn->query('TRUNCATE category');

        $conn->query('SET FOREIGN_KEY_CHECKS = 1');

        $io->text("Tables are now empty...");

        $io->progressStart(20);

        $roles = ["admin","user"];
        $categoriesName = ["Outil","Véhicule","Meuble","Jeu"];
        $categories = [];
        $users = [];

        $user = new User();
        $user->setUsername("test");
        $user->setEmail("test@gmail.com");
        $user->setRoles(["user"]);
        $user->setPassword($this->encoder->encodePassword($user, $user->getUsername()));

        $this->em->persist($user);

        $users[] = $user;

        for($i=0;$i<20;$i++) {
            $io->progressAdvance(1);

            $user = new User();
            $user->setUsername($faker->name);
            $user->setEmail($faker->email);
            $user->setRoles($faker->randomElements($roles));
            $user->setPassword($this->encoder->encodePassword($user, $user->getUsername()));

            $this->em->persist($user);

            $users[] = $user;
        }

        $this->em->flush();
        $io->progressFinish();

        $category = new Category();
        $category->setName("Tous");
        $this->em->persist($category);

        for($i=0; $i<4;$i++)
        {
            $category = new Category();
            $category->setName($categoriesName[$i]);
            $categories[] = $category;
            $this->em->persist($category);
        }

        $this->em->flush();

        $io->progressStart(200);

        for($i=0;$i<200;$i++){
            $io->progressAdvance(1);

            $article = new Article();
            $article->setName($faker->text(20));
            $article->setDescription($faker->text);
            $article->setPrice($faker->randomNumber());
            $article->setArticleCategory($faker->randomElement($categories));
            $article->setUser($faker->randomElement($users));
            $article->setCity($faker->city);
            $article->setZip($faker->numerify("#####"));
            $article->setDateCreated($faker->dateTimeBetween("-1 year", "now"));

            $this->em->persist($article);
        }
        $io->progressFinish();

        $this->em->flush();

        $io->success("!Done");
    }
}