<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Book;

class BooksBrowserTest extends DuskTestCase
{
    private $title = 'Example Book';
    private $author = 'Example Author';
    /**
     * Browser test of book creation.
     *
     * @return void
     */
    public function testFrontendCreationOfBook()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Create +')
                ->click('@create-btn')
                ->assertPathIs('/create')
                ->type('title', $this->title)
                ->type('author', $this->author)
                ->click('@create-btn')
                ->assertPathIs('/')
                ->pause(1000);
        });

        $this->assertDatabaseHas('books', [
            'title' => $this->title,
            'author' => $this->author
        ]);
    }

    /**
     * Browser test of book creation.
     *
     * @return void
     */
    /*public function testFrontendDeletionOfBook()
    {
        $book = Book::create([
            'title' => $this->title,
            'author' => $this->author,
        ]);

        $this->browse(function (Browser $browser) {
            
        });

        $this->assertDeleted('books', [
            'title' => $this->title,
            'author' => $this->author
        ]);
    }*/
}
