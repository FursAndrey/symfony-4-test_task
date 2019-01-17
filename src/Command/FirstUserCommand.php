<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

use App\Service\UserService as US;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FirstUserCommand extends Command
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:first-user')
            ->setDescription('Create a new user.')
            ->setHelp('This command allows you to create user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userService = new US(
            $this->entityManager,
            $this->passwordEncoder
        );

        $login = 'admin';
        $pass = 'qwerty';
        $nickname = 'superadmin';
        $email = 'admin@symfony.local';
        $birthdate = new \DateTime('1900-01-01');

        $result = $userService->create($login, $pass, $nickname, $email, $birthdate);
        if ($result == '') {
            $output->writeln([
                '            <question>                                    </question>',
                '            <question>            User created            </question>',
                '            <question>                                    </question>',
            ]);
        } else {
            $output->writeln([
                '            <error>                                                            </error>',
                "            <error>            $result            </error>",
                '            <error>                                                            </error>',
            ]);
        }
    }
}