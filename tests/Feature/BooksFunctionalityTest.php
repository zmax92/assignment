<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksFunctionalityTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test to test creating book with missing requested data.
     *
     * @return void
     */
    public function testCeateBookWithMissingData()
    {
        $response = $this->post('/', [
            'title' => '',
            'author' => ''
        ]);

        $response->assertSessionHasErrors(['title', 'author']);

        $response = $this->post('/', [
            'title' => '',
            'author' => 'Example Author'
        ]);

        $response->assertSessionHasErrors('title');

        $response = $this->post('/', [
            'title' => 'Example Book',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /**
     * A basic test to test creating book with valid data.
     *
     * @return void
     */
    public function testCeateBookWithBValidData()
    {
        $response = $this->post('/', [
            'title' => 'Example Book',
            'author' => 'Example Author'
        ]);

        $response->assertOk();
    }
}
