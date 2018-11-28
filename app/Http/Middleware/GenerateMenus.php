<?php

namespace App\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		
		\Menu::make('MainMenu', function ($menu) {
			
			$menu->raw("NAWIGACJA", ["class" => "header"]);
			$menu->add("<i class=\"material-icons\">home</i><span>Strona główna</span>", ["route" => "index" ]);
			$menu->add("<i class=\"material-icons\">people</i><span>Grupy</span>", ["route" => "groups" ])->active("groups/*");
            $menu->add("<i class=\"material-icons\">assignment_ind</i><span>Postacie</span>", ["route" => "searchChar" ])->active("characters/*");
            $menu->add("<i class=\"material-icons\">videogame_asset</i><span>Gracze online</span>", ["route" => "onlineChars" ]);
            $menu->add("<i class=\"material-icons\">shopping_basket</i><span>Sklepy</span>", ["route" => "shops.index" ]);
		});
		
        return $next($request);
    }
}
