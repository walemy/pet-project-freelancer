<?php

namespace Freelance\Task\Application\Http\Controllers;

use App\ValueObjects\Id;
use Filterable\Dtos\FilterDto;
use Freelance\Task\Application\Http\Resources\CategoryResource;
use Freelance\Task\Domain\Actions\Contracts\CreatesCategoryAction;
use Freelance\Task\Domain\Actions\Contracts\DeletesCategoryAction;
use Freelance\Task\Domain\Actions\Contracts\GetsPaginatedCategoriesAction;
use Freelance\Task\Domain\Actions\Contracts\ShowsCategoryAction;
use Freelance\Task\Domain\Actions\Contracts\UpdatesCategoryAction;
use Freelance\Task\Domain\Dtos\CategoryDto;
use Freelance\Task\Domain\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

final class CategoryController
{
    use AuthorizesRequests;

    public function index(
        Request $request,
        GetsPaginatedCategoriesAction $action,
    ): ResourceCollection {
        $this->authorize('index', Category::class);
        $filterDto = FilterDto::createFromArrayBag($request->all());
        $paginated = $action->run($filterDto);
        return CategoryResource::collection($paginated);
    }

    public function store(
        Request               $request,
        CreatesCategoryAction $action
    ): JsonResource {
        $this->authorize('create', Category::class);
        $dto = CategoryDto::create(
            $request->input('name'),
            $request->input('parent_id')
        );
        $entity = $action->run($dto);
        return new CategoryResource($entity);
    }

    public function show(
        Id $id,
        ShowsCategoryAction $action,
    ): JsonResource {
        $this->authorize('show', [Category::class, $id]);
        $entity = $action->run($id);
        return new CategoryResource($entity);
    }

    public function update(
        Id $id,
        Request $request,
        UpdatesCategoryAction $action,
    ): JsonResource {
        $this->authorize('update', [Category::class, $id]);
        $dto = CategoryDto::create(
            $request->input('name'),
            $request->input('parent_id')
        );

        $entity = $action->run($id, $dto);
        return new CategoryResource($entity);
    }

    public function destroy(Id $id, DeletesCategoryAction $action): Response
    {
        $this->authorize('delete', [Category::class, $id]);
        $action->run($id);
        return response()->noContent();
    }
}
