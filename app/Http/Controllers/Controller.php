<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

<<<<<<< HEAD
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
=======
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Base controller for the application. Provides middleware(), authorize(),
    // and other common helper methods via the Laravel base controller and traits.
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
}
