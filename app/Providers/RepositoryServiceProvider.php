<?php

namespace App\Providers;

use App\Contracts\ProductContract;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Contracts\ServiceProductContract;
use App\Repositories\ServiceProductRepository;
use App\Contracts\BusinessTypeCategoryContract;
use App\Repositories\BusinessTypeCategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        BusinessTypeCategoryContract::class => BusinessTypeCategoryRepository::class,
        ProductContract::class => ProductRepository::class,
        ServiceProductContract::class => ServiceProductRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation)
        {
            $this->app->bind($interface, $implementation);
        }
    }
}