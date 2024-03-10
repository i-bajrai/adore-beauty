<?php

use App\Models\Promotion;
use App\Services\PromotionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->promotionService = new PromotionService();
});

it('can search promotions by IDs', function () {
    Promotion::factory()->count(3)->create();
    $ids = Promotion::pluck('id')->toArray();

    $request = new Request(['ids' => implode(',', $ids)]);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(3);
});

it('can search promotions by type', function () {
    Promotion::factory()->create(['type' => 'automatic']);
    Promotion::factory()->create(['type' => 'coupon']);

    $request = new Request(['type' => 'automatic']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can search promotions by name', function () {
    Promotion::factory()->create(['name' => 'Promotion A']);
    Promotion::factory()->create(['name' => 'Promotion B']);

    $request = new Request(['name' => 'Promotion A']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can search promotions by start date', function () {
    Promotion::factory()->create(['start_date' => '2022-01-01']);
    Promotion::factory()->create(['start_date' => '2022-01-02']);

    $request = new Request(['start_date' => '2022-01-01']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can search promotions by start year', function () {
    Promotion::factory()->create(['start_date' => '2022-01-01']);
    Promotion::factory()->create(['start_date' => '2023-01-02']);

    $request = new Request(['start_date' => '2022']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can search promotions by end date', function () {
    Promotion::factory()->create(['end_date' => '2022-01-01']);
    Promotion::factory()->create(['end_date' => '2022-01-02']);

    $request = new Request(['end_date' => '2022-01-01']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can search promotions by end year', function () {
    Promotion::factory()->create(['end_date' => '2022-01-01']);
    Promotion::factory()->create(['end_date' => '2023-01-02']);

    $request = new Request(['end_date' => '2022']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can handle request with multiple filters', function () {
    Promotion::factory()->create(['type' => 'automatic', 'name' => 'Promotion A']);
    Promotion::factory()->create(['type' => 'coupon', 'name' => 'Promotion B']);

    $request = new Request(['type' => 'automatic', 'name' => 'Promotion A']);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('can search promotions by is_active', function () {
    Promotion::factory()->create(['is_active' => true]);
    Promotion::factory()->create(['is_active' => false]);

    $request = new Request(['is_active' => true]);
    $result = $this->promotionService->search($request);

    expect($result)->toHaveCount(1);
});

it('returns empty result when no promotions match criteria', function () {
    Promotion::factory()->create(['type' => 'automatic']);

    $request = new Request(['type' => 'coupon']);
    $result = $this->promotionService->search($request);

    expect($result)->toBeEmpty();
});
