<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Book;

class BookController extends Controller
{
    public function index() 
    {
        $books = Book::paginate(30);
        return view('books.index', array(
            'books' => $books
        ));
    }
    public function create()
    {
        return view('books.create');   
    }
    public function import(Request $request)
    {
        Book::truncate();
        $imports = $request->all();
        foreach($imports as $import) {
            DB::table('authors')->insertOrIgnore([
                'name' => $import['author']
            ]);
            if (DB::table('books')->where('title', $import['title'])->count()) {
                continue;
            }
            $book = new Book([
                'title' => $import['title'],
                'slug' => Str::slug($import['title'], '-'),
                'author' => $import['author'],
                'comment' => $import['comment'],
                'rate' => $import['rate']            
            ]);
            $book->save();
        }
        $request->session()->flash('status', 'Votre importation de livres a été un succès.');
        return response()->json(array('status' => 'Votre importation de livres a été un succès.'), 200);
    }
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
        $validator = $this->validator($request->all(), true, $id);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::table('authors')->upsert([
            'name' => $request->author
        ]);
        $book = Book::find($id);
        if ($book) {
            $book->title = $request->title;
            $book->slug = Str::slug($request->title, '-');
            $book->author = $request->author;
            $book->comment = $request->comment;
            $book->rate = $request->rate;
            $book->save();
            return redirect()->route('books.index')->with(array(
                'status' => 'Votre livre a été mis à jour avec succès.'
            ));
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
    protected function validator($requestAll, $update = false, $id = null)
    {
        $rules = array(
            'title' => ['required', 'string:255', $update ? Rule::unique('books')->ignore($id):'unique:books'],
            'author' => ['required', 'string'],
            'comment' => ['required', 'string'],
            'rate' => ['required', 'numeric', 'digits_between:0,20'] 
        );
        $messages = array(
            'title.required' => 'Ce champ est obligatoire.',
            'title.string' => 'Veuillez entrer un titre valide.',
            'title.string' => 'Veuillez entrer un titre de 255 caractères maximum.',
            'title.unique' => 'Ce titre est déjà pris, veuillez entrer un autre.',
            'author.required' => 'Ce champ est obligatoire.',
            'author.string' => 'Veuillez entrer un nom d\'author valide.',
            'comment.required' => 'Ce champ est obligatoire.',
            'comment.string' => 'Veuillez entrer un commentaire valide.',
            'rate.required' => 'Ce champ est obligatoire.',
            'rate.numeric' => 'Veuillez entrer une note valide.',
            'rate.digits_between' => 'Veuillez entrer une note entre 0 à 20.'
        );
        $validator = Validator::make($requestAll, $rules, $messages);
        return $validator;
    }
    protected function bookNotFound()
    {
        return redirect()->route('books.index')->with(array(
            'warning' => 'Ce livre n\'existe pas.'
        ));
    }
}
