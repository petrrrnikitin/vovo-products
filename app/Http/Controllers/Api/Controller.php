<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version : '1.0.1',
    title : 'Vovo Marketplace API',
)]
#[OA\Server(
    url : L5_SWAGGER_CONST_HOST,
    description : 'API Server',
)]
class Controller
{
}
