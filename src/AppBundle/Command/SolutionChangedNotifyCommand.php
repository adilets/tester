<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SolutionChangedNotifyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:solution-changed-notify')
            ->setDescription('Notifies websocket that solution changed')
            ->setHelp('This command send notification to websocket that new solution or solution\'s status changed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $pusher = $container->get('gos_web_socket.wamp.pusher');

        $solutions = $em->getRepository('AppBundle:Solution')->findBy([], ['id' => 'DESC'], 20);

        $content = $container->get('templating')->render("::_solutions.html.twig", [
            'solutions' => $solutions
        ]);

        $pusher->push(['solutions' => $content], 'app_topic_websocket');
    }
}