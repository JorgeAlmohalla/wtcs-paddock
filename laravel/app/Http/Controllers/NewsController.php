<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __invoke(): View
    {
        // Sacamos las noticias ordenadas por fecha, 9 por página
        $posts = Post::orderBy('published_at', 'desc')->paginate(9);

        return view('news', [
            'posts' => $posts,
        ]);
    }
}