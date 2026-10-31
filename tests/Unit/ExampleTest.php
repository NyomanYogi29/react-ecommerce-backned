<?php

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('This should return 200', function () {
    $response = $this->get("/");

    $response->assertStatus(200);
});