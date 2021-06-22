<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => [ "required", "string", "max:255", "unique:categories"]
        ], [
            "name.required" => "Le nom est obligatoire.",
            "name.string" => "Veuillez entrer une chaine de caractères valide.",
            "name.max" => "Veuillez entrer au maximum 255 caractères.",
            "name.unique" => "Cette catégorie existe déjà. Veuillez en choisir une autre.",
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.categories.create')
                ->withErrors($validator)
                ->withInput();
        }

        $category = Category::create(['name' => $request->name]);
        return redirect()->route('admin.categories.index')->with(array(
            'status' => 'Votre catégorie a été ajoutée avec sucèss.'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return view('admin.categories.show', array(
                'category' => $category
            ));
        }
        return redirect()->route('admin.categories.index')->with(array(
            'warning' => "Cette catégorie n'existe pas."
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            return view('admin.categories.edit', array(
                'category' => $category
            ));
        }
        return redirect()->route('admin.categories.index')->with(array(
            'warning' => "Cette catégorie n'existe pas."
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $validator = Validator::make($request->all(), [
            "name" => [ "required", "string", "max:255", Rule::unique('categories')->ignore($id) ]
        ], [
            "name.required" => "Le nom est obligatoire.",
            "name.string" => "Veuillez entrer une chaine de caractères valide.",
            "name.max" => "Veuillez entrer au maximum 255 caractères.",
            // "name.exists" => "Cette catégorie n'existe pas.",
            "name.unique" => "Cette catégorie existe déjà. Veuillez en choisir une autre.",
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $category->update(['name' => $request->name]);
        return redirect()->route('admin.categories.index')->with(array(
            'status' => "Votre catégorie a été modifiée avec sucèss."
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->route("admin.categories.index")->with([
            "status" => "Votre catégorie: <u>".$category->name."</u> a été supprimée avec succès."
        ]);
    }
}
