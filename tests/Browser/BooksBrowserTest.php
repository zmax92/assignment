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

    /**
     * Browser test of updating books author.
     *
     * @return void
     */
    public function testFrontendUpdateAuthor()
    {
        $query = DB::table('books');
        $books = $query->get();
        $books = $books->toArray();

        if(!empty($books)) {
            // Database is populated, randomly select book to update author
            $this->child = count($books);

            if($this->child > 1) {
                $this->child = rand(0, $this->child - 1);
                // child for books array is value 0 to n
                $this->book = $books[ $this->child ];

                // child for dusk asserts is value 1 to n
                $this->child = $this->child + 1;
            }
            else {
                $this->book = $books[0];
                $this->child = 1;
            }

        }
        else {
            // Database is empty, create book
            $this->book = Book::create([
                'title' => $this->title,
                'author' => $this->author,
            ]);
        }

        $this->selector = '.books-list tr:nth-child('.$this->child.')';
        $this->edit_btn = $this->selector.' .edit-icon';
        $this->input = $this->selector.' .input-wrapper input';
        $this->alert = $this->selector.' .alert-danger';
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->ensurejQueryIsAvailable();
            $browser->assertVisible($this->edit_btn)
                ->click($this->edit_btn)
                ->assertVisible($this->input)
                ->type($this->input, '')
                ->assertVisible($this->alert)
                ->type($this->input, $this->author);
            $browser->script('return $("'.$this->input.'").blur();');
            $browser->pause(1000); // pause, because blur does submit to backend
            $browser->assertVisible($this->edit_btn);
        });

        $this->assertDatabaseHas('books', [
            'id' => $this->book->id,
            'author' => $this->author
        ]);
    }
}
