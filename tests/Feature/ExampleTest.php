<?php

// Root route (/) redirects to /login — this is the expected behavior for SIAKAD
test('the application root redirects to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
