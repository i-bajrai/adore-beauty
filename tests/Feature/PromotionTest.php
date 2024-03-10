<?php

use App\Models\Promotion;
use Illuminate\Http\Response;

use function Pest\Laravel\getJson;

it('can search promotions by IDs', function () {
    $promotions = Promotion::factory()->count(3)->create();
    $ids = $promotions->pluck('id')->toArray();

    getJson('/api/promotions?ids='.implode(',', $ids))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3);
});

it('can search promotions by type', function () {
    Promotion::factory()->create(['type' => 'automatic']);
    Promotion::factory()->create(['type' => 'coupon']);

    getJson('/api/promotions?type=automatic')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);
});

it('can search promotions by name', function () {
    Promotion::factory()->create(['name' => 'Promotion A']);
    Promotion::factory()->create(['name' => 'Promotion B']);

    getJson('/api/promotions?name=Promotion A')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);
});

it('can search promotions by start date', function () {
    Promotion::factory()->create(['start_date' => '2022-01-01']);
    Promotion::factory()->create(['start_date' => '2022-01-02']);

    getJson('/api/promotions?start_date=2022-01-01')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);
});

it('can search promotions by end date', function () {
    Promotion::factory()->create(['end_date' => '2022-01-01']);
    Promotion::factory()->create(['end_date' => '2022-01-02']);

    getJson('/api/promotions?end_date=2022-01-01')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);
});

it('can search promotions by is active (boolean)', function () {
    Promotion::factory()->create(['is_active' => true]);
    Promotion::factory()->create(['is_active' => false]);

    getJson('/api/promotions?is_active=0')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);

    getJson('/api/promotions?is_active=1')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);
});

it('can search promotions by is active (string)', function () {
    Promotion::factory()->create(['is_active' => true]);
    Promotion::factory()->create(['is_active' => false]);

    getJson('/api/promotions?is_active=true')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);

    getJson('/api/promotions?is_active=false')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1);
});

it('returns bad request if IDs are invalid', function () {
    getJson('/api/promotions?ids=invalid')
        ->assertStatus(Response::HTTP_BAD_REQUEST);
});
