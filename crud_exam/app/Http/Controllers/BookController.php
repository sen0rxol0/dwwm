<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;

class BookController extends Controller
{
    public function index() 
    {
        return view('books.index', array(
            'books' => Book::all()
        ));
    }
    public function create()
    {
        return view('books.create');   
    }
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withError($validator)->withInput();
        }
        DB::table('authors')->insert([
            'name' => $request->author
        ]);
        $book = new Book([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'author' => $request->author,
            'comment' => $request->comment,
            'rate' => $request->rate            
        ]);
        $book->save();
        return redirect()->route('books.index')->with(array(
            'status' => 'Votre livre a été ajouté avec succès.'
        ));
    }
    public function show($id) 
    {
        $book =  Book::find($id);
        if ($book) {
            return view('books.show', array(
                'book' => $book
            ));
        }
        return $this->bookNotFound();
    }
    public function edit($id) 
    {
        $book =  Book::find($id);
        if ($book) {
            return view('books.edit', array(
                'book' => $book
            ));
        }
        return $this->bookNotFound();
    }
    public function update(Request $request, $id) 
    {
        $validator = $this->validator($request->all(), true);
        if ($validator->fails()) {
            return redirect()->back()->withError($validator)->withInput();
        }
        $book = Book::find($id);
        if ($book) {

        }
        return $this->bookNotFound();
        
    }
    public function destroy(Request $request, $id) 
    {
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            return redirect()->route('books.index')->with(array(
                'status' => 'Votre livre a été supprimé avec succès.'
            ));
        }
        return $this->bookNotFound();
    }
    protected function validator($requestAll, $update = false)
    {
        $rules = array(
            'title' => ['required', 'string:255'],
            'author' => ['required', 'string'],
            'comment' => ['required', 'string'],
            'rate' => ['required', 'numeric', 'digits_between:0,20'] 
        );
        $rules_update = $rules;
        $messages = array(
            'title.required' => 'Ce champ est obligatoire.',
            'title.string' => 'Veuillez entrer un titre valide.',
            'title.string' => 'Veuillez entrer un titre de 255 caractères maximum.',
            'author.required' => 'Ce champ est obligatoire.',
            'author.string' => 'Veuillez entrer un nom d\'author valide.',
            'comment.required' => 'Ce champ est obligatoire.',
            'comment.string' => 'Veuillez entrer un commentaire valide.',
            'rate.required' => 'Ce champ est obligatoire.',
            'rate.numeric' => 'Veuillez entrer une note valide.',
            'rate.digits_between' => 'Veuillez entrer une note entre 0 à 20.'
        );
        $validator = Validator::make($requestAll, $update ? $rules_update:$rules, $messages);
        return $validator;
    }
    protected function bookNotFound()
    {
        return redirect()->route('books.index')->with(array(
            'warning' => 'Ce livre n\'existe pas.'
        ));
    }
}
