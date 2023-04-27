<?php

namespace Tests\Feature\Api\Group;

use Tests\TestCase;

class GroupStoreTest extends TestCase
{
    public function url(): string
    {
        return '/api/groups';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->postJson($this->url())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_validates_request()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url())
            ->assertStatus(422)
            ->assertInvalid([
                'name' => trans('validation.required', ['attribute' => 'name']),
            ]);
    }

    /** @test */
    public function it_validates_name_minimum_length()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url(), [
                'name' => 'a',
            ])
            ->assertStatus(422)
            ->assertInvalid([
                'name' => trans('validation.min.string', ['attribute' => 'name', 'min' => 3]),
            ]);
    }

    /** @test */
    public function it_validates_name_maximum_length()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url(), [
                'name' => \Str::random(25),
            ])
            ->assertStatus(422)
            ->assertInvalid([
                'name' => trans('validation.max.string', ['attribute' => 'name', 'max' => 24]),
            ]);
    }

    /** @test */
    public function it_creates_group()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url(), [
                'name' => 'test',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('groups', [
            'name' => 'test',
        ]);
    }
}
