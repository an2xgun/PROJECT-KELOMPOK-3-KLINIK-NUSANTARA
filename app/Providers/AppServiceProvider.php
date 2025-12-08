<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
use Carbon\Carbon;
=======
use Illuminate\Support\Facades\View;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
<<<<<<< HEAD
        config(['app.locale' => 'id']);
		Carbon::setLocale('id');
=======
        // Global helper to safely traverse model relations/arrays by path string
        if (! function_exists(__NAMESPACE__ . '\\safe')) {
            function safe($root, string $path, $fallback = '-') {
                try {
                    if (is_null($root)) return $fallback;
                    $parts = explode('.', $path);
                    $current = $root;
                    foreach ($parts as $part) {
                        if (is_null($current)) return $fallback;
                        if (is_array($current)) {
                            $current = $current[$part] ?? null;
                        } else {
                            // Eloquent model or object
                            if (is_object($current) && (isset($current->{$part}) || method_exists($current, '__get') || method_exists($current, 'getAttribute'))) {
                                $current = $current->{$part} ?? null;
                            } else {
                                // property not present
                                return $fallback;
                            }
                        }
                    }
                    return $current ?? $fallback;
                } catch (\Throwable $e) {
                    return $fallback;
                }
            }
        }

        // Share pending pendaftaran count with all views for dokter/admin
        View::composer('*', function ($view) {
            try {
                $count = 0;
                if (Auth::check() && in_array(Auth::user()->role, ['dokter', 'admin'])) {
                    $count = Pendaftaran::whereIn('status_layanan', ['Menunggu', 'Sedang Dilayani'])->count();
                }
                $view->with('pending_pendaftaran_count', $count);

                // Share basic user settings (if any) so layout can apply them
                $userSettings = [];
                if (Auth::check()) {
                    $userSettings = Auth::user()->settings ?? session('user_settings', []);
                } else {
                    $userSettings = session('user_settings', []);
                }
                $view->with('user_settings', $userSettings);
            } catch (\Throwable $e) {
                $view->with('pending_pendaftaran_count', 0);
            }
        });
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }
}
