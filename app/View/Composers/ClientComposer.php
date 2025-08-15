<?php

namespace App\View\Composers;

use App\Models\Genre;
use Illuminate\View\View;

class ClientComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $genres = Genre::all();
        $view->with('genres', $genres);
    }
}
