<?php

namespace Freelance\User\Application\Http\Controllers;

use Freelance\User\Application\Http\Resources\Client\FreelancerResource;
use Freelance\User\Domain\Actions\Contracts\SetsFreelancerProfileAction;
use Freelance\User\Domain\Dtos\FreelancerProfileDto;
use Freelance\User\Domain\Models\Freelancer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class FreelancerController
{
    use AuthorizesRequests;

    public function update(
        Request                     $request,
        SetsFreelancerProfileAction $action
    ): JsonResource {
        $this->authorize('update', Freelancer::class);
        $dto = FreelancerProfileDto::create(
            auth()->id(),
            $request->input('hour_rate'),
        );
        $profile = $action->run($dto);
        return new FreelancerResource($profile);
    }
}
