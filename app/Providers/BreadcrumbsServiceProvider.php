<?php

namespace App\Providers;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\ServiceProvider;
use App\Models\Mannequin;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Breadcrumbs::for('collection.view_prod', function ($trail, $id) {
            $product = Mannequin::find($id);

            if ($product) {
                $trail->push('Collection', route('collection')); // This line adds a link to the 'collection' route
                $trail->push('Product View', route('collection.view_prod', $id)); // This line adds the 'Product View' text
            }
        });
    }
}
