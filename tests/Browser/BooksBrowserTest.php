<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;
use App\Book;

class BooksBrowserTest extends DuskTestCase
{
    //use DatabaseMigrations;
    private $title = 'Example Book';
    private $author = 'Example Author';
    private $book;
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
                ->assertPathIs('/');
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
    public function testFrontendDeletionOfBook()
    {
        $query = DB::table('books');
        $books = $query->get();
        $books = $books->toArray();

        if(!empty($books)){
            // Database is populated, randomly select book to delete
            $nbr = count($books);

            $this->book = $nbr > 1 ? $books[ rand (0, $nbr-1 ) ] : $books[0];
        }
        else{
            // Database is empty, create book
            $this->book = Book::create([
                'title' => $this->title,
                'author' => $this->author,
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@pin-'.$this->book->id)
                ->acceptDialog()
                ->assertPathIs('/')
                ->pause(1000);
        });

        $this->assertDatabaseMissing('books', [
            'id' => $this->book->id
        ]);
    }
}
