<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SystemController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.system.index', [
            'settings' => [
                'Application' => config('app.name'),
                'Environment' => config('app.env'),
                'URL' => config('app.url'),
                'Timezone' => config('app.timezone'),
                'Locale' => config('app.locale'),
                'Debug' => config('app.debug') ? 'On' : 'Off',
                'PHP' => PHP_VERSION,
                'Laravel' => app()->version(),
            ],
        ]);
    }
}
