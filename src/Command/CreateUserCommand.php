<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Question\Question;

use App\Service\UserService as US;
use App\Service\ValidatorService as VS;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    private $entityManager;
    private $passwordEncoder;
    private $validatorService;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, VS $validatorService)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validatorService = $validatorService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-user')
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
        $inputValue = [
            [
                'type' => 'name',
                'text' => "Please enter the login\n",
            ],
            [
                'type' => 'pass',
                'text' => "Please enter the pass\n",
            ],
            [
                'type' => 'name',
                'text' => "Please enter the nickname\n",
            ],
            [
                'type' => 'email',
                'text' => "Please enter the email\n",
            ],
            [
                'type' => 'date',
                'text' => "Please enter the birthdate\n",
            ],
        ];
        $helper = $this->getHelper('question');
        foreach ($inputValue as $v) {
            $type = $v['type'];
            $text = $v['text'];
            do {
                $question = new Question ($text);
                if ($type == 'pass') {
                    $question->setHidden(true);
                }
                $inputStr = $helper->ask($input, $output, $question);

                $result = $this->validatorService->isValid($type, $inputStr);
                if ($result != []) {
                    $result = implode("\n            ", $result);
                    $output->writeln([
                        "<fg=red;options=bold>            $result\n</>",
                    ]);
                }
            } while ($result != []);
            $inptData[] = $inputStr;
        }
        list($login, $pass, $nickname, $email, $birthdate) = $inptData;
        $birthdate = new \DateTime($birthdate);

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