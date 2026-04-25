<?php

declare(strict_types=1);

namespace App\Search\Indexing;

use App\Models\Product;
use App\Search\Contracts\ProductIndexerInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;

readonly class ElasticsearchProductIndexer implements ProductIndexerInterface
{
    public function __construct(
        private Client $client,
        private string $index,
        private ProductDocumentMapper $documentMapper,
    ) {}

    public function index(Product $product): void
    {
        $this->client->index([
            'index' => $this->index,
            'id'    => $product->id,
            'body'  => $this->documentMapper->toDocument($product),
        ]);
    }

    public function delete(int $id): void
    {
        try {
            $this->client->delete(['index' => $this->index, 'id' => $id]);
        } catch (ClientResponseException $e) {
            if ($e->getResponse()->getStatusCode() !== 404) {
                throw $e;
            }
        }
    }

    /** @param iterable<Product> $products */
    public function bulkIndex(iterable $products): void
    {
        $body = [];

        foreach ($products as $product) {
            $body[] = ['index' => ['_id' => $product->id]];
            $body[] = $this->documentMapper->toDocument($product);
        }

        if (!empty($body)) {
            $this->client->bulk(['index' => $this->index, 'body' => $body]);
        }
    }

    public function createIndex(): void
    {
        if ($this->client->indices()->exists(['index' => $this->index])->asBool()) {
            $this->client->indices()->delete(['index' => $this->index]);
        }

        $this->client->indices()->create([
            'index' => $this->index,
            'body'  => [
                'mappings' => [
                    'properties' => [
                        'id'         => ['type' => 'integer'],
                        'name'       => ['type' => 'text'],
                        'price'      => ['type' => 'double'],
                        'category_id'=> ['type' => 'integer'],
                        'in_stock'   => ['type' => 'boolean'],
                        'rating'     => ['type' => 'float'],
                        'created_at' => ['type' => 'date'],
                    ],
                ],
            ],
        ]);
    }
}