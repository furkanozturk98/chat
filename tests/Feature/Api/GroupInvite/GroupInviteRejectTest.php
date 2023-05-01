<?php

namespace Tests\Feature\Api\GroupInvite;

use Tests\TestCase;

class GroupInviteRejectTest extends TestCase
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
        $this->putJson($this->url() . '1/reject')
            ->assertUnauthorized();
    }
}
