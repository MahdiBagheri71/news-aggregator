<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Private;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Private\ArticleRequest;
use App\Http\Resources\Api\V1\Private\Article\ArticleCollection;
use App\Services\Actions\ArticleFilterAction;
use Illuminate\Http\Response;

/**
 * @tag Article
 */
class ArticleController extends Controller
{
    /**
     * @authenticated
     */
    public function index(ArticleRequest $request, ArticleFilterAction $articleFilterAction): ArticleCollection
    {
        $articles = $articleFilterAction->handle($request->validationData());

        // @status 200
        return new ArticleCollection($articles, __('success'), Response::HTTP_OK);
    }
}
