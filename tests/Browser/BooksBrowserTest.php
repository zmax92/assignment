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
                ->click('.create-wrapper .create-btn')
                ->assertPathIs('/create')
                ->type('title', '')
                ->type('author', '')
                ->click('.create-form .create-btn')
                ->assertPathIs('/create')
                ->assertVisible('.create-form .alert-danger')
                ->type('title', $this->title)
                ->type('author', $this->author)
                ->click('.create-form .create-btn')
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

        $this->getRandom($books, 'delete_book');

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertVisible($this->delete_btn)
                ->click($this->delete_btn)
                ->acceptDialog()
                ->assertPathIs('/')
                //pause because test done too quickly and it's still not deleted from database
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

        $this->getRandom($books, 'update_author');
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

    /**
     * Select random or create new book for testing
     *
     * @param  array  $books
     * @param  string $test
     *  Variable to help set, if needed, other params of test
     * @return void
     */
    private function getRandom($books, $test = ''){
        $this->child = 1;

        if(!empty($books)) {
            // Database is populated, randomly select book for test
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
            }
        }
        else {
            // Database is empty, create book
            $this->book = Book::create([
                'title' => $this->title,
                'author' => $this->author,
            ]);
        }

        if(!empty($test)){
            switch($test){
                case 'update_author':
                    $this->selector = '.books-list tr:nth-child('.$this->child.')';
                    $this->edit_btn = $this->selector.' .edit-icon';
                    $this->input = $this->selector.' .input-wrapper input';
                    $this->alert = $this->selector.' .alert-danger';
                break;
                case 'delete_book':
                    $this->selector = '.books-list tr:nth-child('.$this->child.')';
                    $this->delete_btn = $this->selector.' .delete-btn';
                break;
            }
        }
    }
}
