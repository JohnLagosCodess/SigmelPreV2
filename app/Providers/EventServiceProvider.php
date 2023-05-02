<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\RolesController;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            
            $menu_final = (new RolesController)->crear_menu();
            
            // echo "<pre>";
            //     print_r($menu_final);
            //     echo "</pre>";

            for ($i=0; $i < count($menu_final); $i++) { 

                if(!array_key_exists('submenu', $menu_final[$i]) ){
                    $event->menu->add(
                        [
                            'text' => $menu_final[$i]['text'],
                            'icon' => $menu_final[$i]['icon'],
                            'url' => $menu_final[$i]['url']
                        ]
                    );
                }else{

                    $event->menu->add(
                        [
                            'key' => $menu_final[$i]['text'],
                            'text' => $menu_final[$i]['text'],
                            'icon' => $menu_final[$i]['icon'],
                        ]
                    );

                    for ($c=0; $c < count($menu_final[$i]['submenu']); $c++) { 
                        $event->menu->addIn($menu_final[$i]['text'],[

                            'text' => $menu_final[$i]['submenu'][$c]['text'],
                            'icon' => $menu_final[$i]['submenu'][$c]['icon'],
                            'url' => $menu_final[$i]['submenu'][$c]['url']
                        ]);
                        
                    };
                }
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
