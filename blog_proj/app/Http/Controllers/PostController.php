<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\Category;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.posts.index', array(
            'posts' => $posts
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', array(
            'categories' => $categories
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateRequestPost();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $imagePath = $this->storePostImage($request->image);
        $category = Category::find($request->category_id);
        $post = new Post([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'image' => $imagePath,
            'content' => $request->content
        ]);
        $post->user()->associate(Auth::user());
        $post->category()->associate($category);
        $post->save();
        
        return redirect()->route('admin.posts.index')->with(array(
            'status' => "Votre article a été enregistré avec succès."
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
        $post = Post::find($id);
        if (!$post) {
            return redirect()->route('admin.posts.index')->with(array(
                'warning' => 'Cet article n\'existe pas.'
            ));
        }

        return view('admin.posts.show', array(
            'post' => $post
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
        $post = Post::find($id);
        if (!$post) {
            return redirect()->route('admin.posts.index')->with(array(
                'warning' => 'Cet article n\'existe pas.'
            ));
        }

        return view('admin.posts.edit', array(
            'post' => $post,
            'categories' => Category::all()
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
        $post = Post::find($id);
        if ($post) {
            if ($request->publish || $request->has('published')) {
                $post->update(array('published' => $request->published));
                return redirect()->route('admin.posts.index')->with(array(
                    'status' => 'Votre article a été mise á jour avec succès.'
                ));
            }

            $validator = $this->validateRequestPost(true);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($request->hasFile('image')) {
                if($post->image) {
                    Storage::delete($post->image);
                }
                $post->image = $this->storePostImage($request->image);
            }

            $post->title = $request->title;
            $post->slug = Str::slug($request->title, '-');
            $post->content = $request->content;
            $category = Category::find($request->category_id);
            $post->category()->associate($category);
            $post->save();
            return redirect()->route('admin.posts.index')->with(array(
                'status' => 'Votre article a été mise á jour avec succès.'
            ));
        } else {
            return redirect()->route('admin.posts.index')->with(array(
                'warning' => 'Cet article n\'existe pas.'
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function validateRequestPost($update = false) 
    {
        if ($update) {
            return Validator::make($request->all(), array(
                "title" => ["required", "string", "max:255"],
                "category_id" => ["required", "integer", "exists:categories,id"],
                // "image" => ["required", "image", "dimensions:min_width=100,min_height=100"],
                "content" => ["required", "string"]
            ), array(
                "title.required" => "Ce champ est obligatoire.",
                "title.string" => "Veuillez entrer un titre valide.",
                "title.max" => "Veuillez entrer un titre de 255 catactères maximum.",
                "category_id.required" => "Ce champ est obligatoire.",
                "category_id.integer" => "Ce champ doit être un nombre entier.",
                "category_id.exists" => "Cette catégorie n'existe pas.",
                // "image.required" => "Ce champ est obligatoire.",
                // "image.image" => "Ce type de fichier n'est pas supporté.",
                // "image.dimensions" => "Veuillez insérer une image d'au moins 100x100px",
                "content.required" => "Ce champ est obligatoire.",
                "content.string" => "Format invalide."
            ));
        }
        return Validator::make($request->all(), array(
            "title" => ["required", "string", "max:255"],
            "category_id" => ["required", "integer", "exists:categories,id"],
            "image" => ["required", "image", "dimensions:min_width=100,min_height=100"],
            "content" => ["required", "string"]
        ), array(
            "title.required" => "Ce champ est obligatoire.",
            "title.string" => "Veuillez entrer un titre valide.",
            "title.max" => "Veuillez entrer un titre de 255 catactères maximum.",
            "category_id.required" => "Ce champ est obligatoire.",
            "category_id.integer" => "Ce champ doit être un nombre entier.",
            "category_id.exists" => "Cette catégorie n'existe pas.",
            "image.required" => "Ce champ est obligatoire.",
            "image.image" => "Ce type de fichier n'est pas supporté.",
            "image.dimensions" => "Veuillez insérer une image d'au moins 100x100px",
            "content.required" => "Ce champ est obligatoire.",
            "content.string" => "Format invalide."
        ));
    }

    /**
     * @return string $imagePath
     */
    protected function storePostImage($image): string 
    {
        $imageName = uniqid('post_').'_'.date('Y-m-d', time()).'_'.$image->getClientOriginalName();
        $image->move('uploads/posts/images/', $imageName);
        return 'uploads/posts/images/'.$imageName;

    }
}
