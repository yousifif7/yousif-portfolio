<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class MaintenanceController extends Controller
{
    public function show()
    {
        return view('admin.maintenance.index');
    }

    public function runPostDeploy(Request $request)
    {
        $commands = [
            'images:optimize' => [],
            'view:clear' => [],
            'cache:clear' => [],
            'config:clear' => [],
        ];

        $results = [];

        foreach ($commands as $command => $parameters) {
            $output = new BufferedOutput;

            try {
                $exitCode = Artisan::call($command, $parameters, $output);
                $results[] = [
                    'command' => 'php artisan '.$command,
                    'success' => $exitCode === 0,
                    'output' => trim($output->fetch()) ?: '(completed with no output)',
                ];
            } catch (\Throwable $e) {
                $results[] = [
                    'command' => 'php artisan '.$command,
                    'success' => false,
                    'output' => $e->getMessage(),
                ];
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => collect($results)->every(fn ($r) => $r['success']),
                'results' => $results,
            ]);
        }

        return view('admin.maintenance.index', [
            'results' => $results,
            'ran' => true,
        ]);
    }
}
