<?php

namespace Longway\Frame\Test\Behat\AOP;

use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class InitTrait
{

    use \Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

    protected $app;

    /**
     * @BeforeSuite
     */
    public static function testing(BeforeSuiteScope $scope)
    {
        $_ENV['APP_ENV'] = 'testing';
        putenv('APP_ENV=testing');
    }

    /**
     * @BeforeScenario
     */
    public function app(BeforeScenarioScope $scope)
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $this->app = $app;
    }
}