<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(302)
        ->assertRedirect(route('scramble.docs.ui'));
});
