<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Tag;

class TagController extends Controller
{
    public function __constructor()
    {
        $this->middleware(array('auth', 'admin'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', [
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
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
            "name" => [ "required", "string", "max:255", "unique:tags"]
        ], [
            "name.required" => "Le nom est obligatoire.",
            "name.string" => "Veuillez entrer une chaine de caractères valide.",
            "name.max" => "Veuillez entrer au maximum 255 caractères.",
            "name.unique" => "Ce tag existe déjà. Veuillez en choisir un autre.",
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.tags.create')
                ->withErrors($validator)
                ->withInput();
        }

        $category = Tag::create(['name' => $request->name]);
        return redirect()->route('admin.tags.index')->with(array(
            'status' => 'Votre tag a été ajouté avec sucèss.'
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
        $tag = Tag::find($id);
        if ($tag) {
            return view('admin.tags.show', array(
                'tag' => $tag
            ));
        }
        return redirect()->route('admin.tags.index')->with(array(
            'warning' => "Ce tag n'existe pas."
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
        $tag = Tag::find($id);
        if ($tag) {
            return view('admin.tags.edit', array(
                'tag' => $tag
            ));
        }
        return redirect()->route('admin.tags.index')->with(array(
            'warning' => "Ce tag n'existe pas."
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
        $tag = Tag::find($id);
        $validator = Validator::make($request->all(), [
            "name" => [ "required", "string", "max:255", Rule::unique('tags')->ignore($id) ]
        ], [
            "name.required" => "Le nom est obligatoire.",
            "name.string" => "Veuillez entrer une chaine de caractères valide.",
            "name.max" => "Veuillez entrer au maximum 255 caractères.",
            // "name.exists" => "Ce tag n'existe pas.",
            "name.unique" => "Ce tag existe déjà. Veuillez en choisir un autre.",
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $tag->update(['name' => $request->name]);
        return redirect()->route('admin.tags.index')->with(array(
            'status' => "Votre tag a été modifié avec sucèss."
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
        $tag = Tag::find($id);
        $tag->delete();
        return redirect()->route("admin.tags.index")->with([
            "status" => "Votre tag: <u>".$tag->name."</u> a été supprimé avec succès."
        ]);
    }
}
