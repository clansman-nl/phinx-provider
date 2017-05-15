<?php

namespace Basebuilder\PhinxProvider;

use Phinx\Console\Command;
use Symfony\Component\Console\Application as ConsoleApplication;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Registers all Phinx commands on a console service
 */
class PhinxProvider implements ServiceProviderInterface
{
    protected $serviceName;

    public function __construct($serviceName = 'console')
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        if (!isset($app[$this->serviceName])) {
            return;
        }

        if (!$app[$this->serviceName] instanceof ConsoleApplication) {
            return;
        }

        $app[$this->serviceName] = $app->share($app->extend($this->serviceName, function (ConsoleApplication $console) {
            $console->addCommands([
                new Command\Init(),
                new Command\Create(),
                new Command\Migrate(),
                new Command\Rollback(),
                new Command\Status(),
                new Command\Breakpoint(),
                new Command\Test(),
                new Command\SeedCreate(),
                new Command\SeedRun()
            ]);

            return $console;
        }));
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {

    }
}
