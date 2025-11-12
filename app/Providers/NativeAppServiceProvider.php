<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Notification;
use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        // Configure main window for mobile-like experience
        Window::open()
            ->title('Couple Planner 💕')
            ->width(400)  // Mobile-like width
            ->height(700) // Mobile-like height
            ->minWidth(350)
            ->minHeight(600)
            ->resizable(true)
            ->alwaysOnTop(false)
            ->showDevTools(false)
            ->route('dashboard');

        // Set up notifications for date reminders
        $this->setupNotifications();
    }

    /**
     * Setup notifications for the couple planner app
     */
    protected function setupNotifications(): void
    {
        // This can be expanded to handle date reminders, anniversary notifications, etc.
        Notification::title('Couple Planner')
            ->message('Welcome to your relationship planner! 💕')
            ->show();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'memory_limit' => '256M',
            'max_execution_time' => '120',
            'upload_max_filesize' => '10M',
            'post_max_size' => '10M',
        ];
    }
}
