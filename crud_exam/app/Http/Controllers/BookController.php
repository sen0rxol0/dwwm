<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $validator = $this->validator();
        if ($validator->fails()) {
            return redirect()->back()->withError($validator)->withInput();
        }
    }
    public function show($id) 
    {
        return view('books.show', array(
            'book' => Book::findOrFail($id)
        ));
    }
    public function edit($id) 
    {
        return view('books.edit', array(
            'book' => Book::findOrFail($id)
        ));
    }
    public function update(Request $request, $id) 
    {
        
    }
    public function destroy(Request $request, $id) 
    {
        
    }
    protected function validator($update = false)
    {
        $rules = array(
            'title' => ['required', 'string:255'],
            'author' => ['required', 'string'],
            'comment' => ['required', 'text'],
            'rate' => ['required', 'integer'] 
        );
        $messages = array(
            'title.required' => '',
            'author.required' => '',
            'comment.required' => '',
            'rate.required' => '',
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        return $validator;
    }
}
