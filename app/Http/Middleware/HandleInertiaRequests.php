<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            ...parent::share($request),
            'auth' => fn () => [
                'user' => $request->user() ? $request->user()->only('id', 'name', 'email') : null,
            ],
            'errors' => fn () => $request->session()->get('errors')
                ? $request->session()->get('errors')->getBag('default')->toArray()
                : (object) [],
            'toast' => function () use ($request) {
                $result = [];

                $toastSettings = [
                    'toast_success' => [
                        "type" => "success",
                        "label" => "Success message"
                    ],
                    'toast_error' => [
                        "type" => "error",
                        "label" => "Error message"
                    ],
                    'toast_info' => [
                        "type" => "info",
                        "label" => "Infor message"
                    ],
                    'toast_warn' => [
                        "type" => "warn",
                        "label" => "Warning message"
                    ],
                    'toast_secondary' => [
                        "type" => "secondary",
                        "label" => "Message"
                    ],
                    'toast_contrast' => [
                        "type" => "contrast",
                        "label" => "Contrast message"
                    ],
                ];

                foreach ($toastSettings as $type => $toastSetting) {
                    $message = $request->session()->get($type);
                    if ($message) {
                        $result[] = ['type' => $toastSetting['type'], 'label' => $toastSetting['label'], 'message' => $message];
                    }
                }

                return $result;
            }
        ]);
    }
}
