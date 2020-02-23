<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            [
                'title' => '1984',
                'author' => 'George Orwell'
            ],
            [
                'title' => 'Fahrenheit 451',
                'author' => 'Ray Bradbury'
            ],
            [
                'title' => 'The Final Empire',
                'author' => 'Brandon Sanderson'
            ],
            [
                'title' => 'With Fire and Sword',
                'author' => 'Sienkiewicz Henryk'
            ],
            [
                'title' => 'The Art of War',
                'author' => 'Sun Tzu'
            ],
            [
                'title' => 'Promise of Blood',
                'author' => 'Brian McClellan'
            ],
            [
                'title' => 'The Red Knight',
                'author' => 'Miles Cameron'
            ],
            [
                'title' => 'Na Drini ćuprija',
                'author' => 'Ivo Andrić'
            ],
            [
                'title' => 'Sumljivo lice',
                'author' => 'Branislav Nušić'
            ],
            [
                'title' => 'Lolita',
                'author' => 'Vladimir Nabokov'
            ],
        ];

        foreach($books as $book){
            $book = array_merge($book, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('books')->insert($book);
        }
    }
}
