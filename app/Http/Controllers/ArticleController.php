<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\IndexRequest;
use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\UpdateRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\Article\ArticleShortResource;
use App\Models\Article;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->only([
                'store',
                'update',
                'destroy'
            ]);
    }

    public function index(IndexRequest $request)
    {
        return ArticleShortResource::collection(
            Article::query()
                ->orderByDesc('created_at')
                ->paginate()
        );
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    public function store(StoreRequest $request)
    {
        $user = $request->user();
        $article = $user->articles()->create($request->validated());

        return response()->json([
            'message' => 'new article create success',
            'article' => new ArticleResource($article)
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $article->update($request->validated());

        return response()->json([
            'message' => 'new article update success',
            'article' => new ArticleResource($article)
        ]);
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([
            'message' => 'new article delete success',
            'article' => new ArticleResource($article)
        ]);
    }
}
