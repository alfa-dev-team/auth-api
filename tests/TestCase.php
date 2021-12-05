<?php

namespace AlfaDevTeam\AuthApi\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{

    public function createApplication()
    {
        // TODO: Implement createApplication() method.
    }

    protected function getPackageProviders($app)
    {
        return [
            'AlfaDevTeam\AuthApi\Providers\ServiceProvider'
        ];
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }
}