<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePostRequest;
use App\Models\Post;
use App\Models\UserLikesPost;
use App\Rules\SlugValidation;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function list(Request $request): Response
    {
        $searchTerm = $request->query->getString('searchTerm');
        $posts = Post::published()->mostRecentlyPublishedFirst();

        if ($searchTerm) {
            $posts->filterResults($searchTerm);

        }

        $posts->withCount('users');
        $posts->with('users');

        return Inertia::render('Posts', [
            'posts' => $posts->get(),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function save(SavePostRequest $request)
    {
        $validatedData = $request->validate([
            'title' => [
                'required',
                'max:255',
            ],
            'slug' => [
                'required',
                'unique:posts',
                'max:255',
                new SlugValidation,
            ],
        ]);

        Post::create($validatedData);

        return to_route('post.list');
    }

    public function toggleLike(Request $request)
    {
        if (!auth()->user()) {
            return to_route('post.list', [
                'errors' => [
                    'message' => 'Cannot authenticate user',
                ]
            ]);
        }

        $ident = $request->post('ident');
        $ident = htmlspecialchars($ident, ENT_QUOTES);

        if (empty($ident)) {
            return to_route('post.list', [
                'errors' => [
                    'message' => 'No post id provided',
                ]
            ]);
        }

        if (!UserLikesPost::toggleLike($ident)) {
            return to_route('post.list', [
                'success' => [
                    'message' => 'Like status updated',
                ]
            ]);
        }

        return to_route('post.list', [
            'errors' => [
                'message' => 'Something went wrong',
            ]
        ]);
    }
}
