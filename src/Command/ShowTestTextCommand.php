<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use Doctrine\ORM\EntityManagerInterface;

class ShowTestTextCommand extends Command
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:show-text')
            ->setDescription('Create a new user.')
            ->setHelp('This command allows you to create user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output -> writeln ([
            'User Creator' ,
            'Примеры цветов: цвет шрифта; цвет фона',
            '<fg=black;bg=cyan>============</>' ,
            '<fg=red;bg=yellow>============</>' ,
            '<fg=green;bg=magenta>============</>' ,
            '<fg=white;bg=blue>============</>' ,
            'Примеры стиле (перечисление через ",")',
            '<options=bold>bold</>',
            '<options=underscore>underscore</>',
            '<options=blink>blink</>',
            '<options=reverse>reverse</>',
            '<options=conceal>conceal</>',
            '<options=bold,underscore>qqq</>',
        ]);

        $output -> writeln ( '<error>Whoa!</error>' );
        $output -> write ( '<fg=green>You have just </>' );
        $output -> writeln ( '<question>wrote a text</question>. ' );

//        $outputStyle = new OutputFormatterStyle('red', 'yellow', array('bold', 'reverse'));
//        $output->getFormatter()->setStyle('fire', $outputStyle);
//
//        $output->writeln('<fire>foofoofoofoofoo</fire>');
    }
}