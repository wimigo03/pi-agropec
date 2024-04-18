<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    protected $namespace = 'App\Http\Controllers';
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        /*RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });*/

        $this->configureRateLimiting();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        /*$this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });*/
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function mapApiRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/balance-general-f-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/balance-general-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/libro-mayor-cuenta-general-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/libro-mayor-cuenta-general-f-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/libro-mayor-auxiliar-general-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/libro-mayor-auxiliar-general-f-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/estado-resultado-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/estado-resultado-f-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/configuracion-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/inicio-mes-fiscal-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/clientes-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/users-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/cargos-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/categorias-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/productos-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/unidades-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/personal-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/horarios-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/sucursal-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/empresas-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/roles-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/permissions-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/plan-cuentas-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/plan-cuentas-auxiliar-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/zonas-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/mesas-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/precio-productos-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/tipo-precios-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/tipo-cambio-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/asiento-automatico-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/caja-venta-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/comprobantes-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/comprobantesf-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/balance-apertura-route.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/balance-apertura-f-route.php'));
    }
}
