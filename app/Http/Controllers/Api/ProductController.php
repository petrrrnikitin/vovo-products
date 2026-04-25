<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Search\Contracts\ProductSearchInterface;
use App\Search\Exceptions\SearchException;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Resources\ProductResource;
use App\Http\Transformers\ProductSearchFiltersTransformer;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductSearchInterface $search,
        private readonly ProductSearchFiltersTransformer $transformer,
    ) {}

    #[OA\Get(
        path: '/api/products',
        summary: 'Поиск товаров с фильтрами и сортировкой',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'q',
                description: 'Полнотекстовый поиск по названию товара',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255),
            ),
            new OA\Parameter(
                name: 'price_from',
                description: 'Минимальная цена',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'number', minimum: 0),
            ),
            new OA\Parameter(
                name: 'price_to',
                description: 'Максимальная цена',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'number', minimum: 0),
            ),
            new OA\Parameter(
                name: 'category_id',
                description: 'Фильтр по ID категории',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
            ),
            new OA\Parameter(
                name: 'in_stock',
                description: 'Фильтр по наличию на складе',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'boolean'),
            ),
            new OA\Parameter(
                name: 'rating_from',
                description: 'Минимальный рейтинг (0–5)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type : 'number', maximum : 5, minimum : 0),
            ),
            new OA\Parameter(
                name: 'sort',
                description: 'Порядок сортировки',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['price_asc', 'price_desc', 'rating_desc', 'newest'],
                ),
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Количество результатов на странице (1–100, по умолчанию 20)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type : 'integer', maximum : 100, minimum : 1),
            ),
            new OA\Parameter(
                name: 'cursor',
                description: 'Курсор для перехода на следующую/предыдущую страницу',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список товаров с пагинацией',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'price', type: 'string'),
                                    new OA\Property(property: 'in_stock', type: 'boolean'),
                                    new OA\Property(property: 'rating', type: 'number'),
                                    new OA\Property(
                                        property: 'category',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer'),
                                            new OA\Property(property: 'name', type: 'string'),
                                        ],
                                        type: 'object',
                                    ),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                ],
                            ),
                        ),
                        new OA\Property(
                            property: 'links',
                            properties: [
                                new OA\Property(property: 'prev', type: 'string', nullable: true),
                                new OA\Property(property: 'next', type: 'string', nullable: true),
                            ],
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'path', type: 'string'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'next_cursor', type: 'string', nullable: true),
                                new OA\Property(property: 'prev_cursor', type: 'string', nullable: true),
                            ],
                            type: 'object',
                        ),
                    ],
                ),
            ),
            new OA\Response(response: 422, description: 'Ошибка валидации'),
            new OA\Response(response: 500, description: 'Внутренняя ошибка сервера'),
        ],
    )]
    public function index(ProductFilterRequest $request): JsonResponse
    {
        try {
            $result = $this->search->search($this->transformer->fromRequest($request));

            return response()->json([
                'data' => ProductResource::collection($result->items),
                'links' => [
                    'prev' => $result->prevCursor
                        ? $request->fullUrlWithQuery(['cursor' => $result->prevCursor])
                        : null,
                    'next' => $result->nextCursor
                        ? $request->fullUrlWithQuery(['cursor' => $result->nextCursor])
                        : null,
                ],
                'meta' => [
                    'path' => $request->url(),
                    'per_page' => $result->perPage,
                    'next_cursor' => $result->nextCursor,
                    'prev_cursor' => $result->prevCursor,
                ],
            ]);
        } catch (SearchException) {
            return response()->json(['message' => 'Внутренняя ошибка сервера'], 500);
        }
    }
}
