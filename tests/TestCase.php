<?php

namespace projectmentor\UUID\Test;

use projectmentor\UUID\UUIDFacade;
use projectmentor\UUID\UUIDServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return projectmentor\UUID
     */
    protected function getPackageProviders($app)
    {
        return [UUIDServiceProvider::class];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'UUID' => UUIDFacade::class,
        ];
    }
}

