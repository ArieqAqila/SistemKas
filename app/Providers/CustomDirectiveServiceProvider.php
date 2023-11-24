<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CustomDirectiveServiceProvider extends ServiceProvider
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
        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->hak_akses === 'admin'): ?>";
        });

        Blade::directive('endadmin', function () {
            return '<?php endif; ?>';
        });
    }
}
