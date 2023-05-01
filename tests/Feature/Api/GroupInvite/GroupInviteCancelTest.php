<?php

namespace Tests\Feature\Api\GroupInvite;

use Tests\TestCase;

class GroupInviteCancelTest extends TestCase
{
    /**
     * @return string
     */
    public function url(): string
    {
        return '/api/group-invites/';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->deleteJson($this->url() . '1/cancel')
            ->assertUnauthorized();
    }
}
