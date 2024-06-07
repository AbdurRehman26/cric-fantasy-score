<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', [Page::class, $this->getCurrentProject()]);

        return view('pages.index', [
            'pages' => $this->getCurrentProject()->pages,
        ]);
    }
}
