<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    
    public function index()
    {
        return view('posts.index', ['posts' => Post::latest()->get()]);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        $response = Gate::inspect('view', $post);
        if($response->allowed()){
            return view('post', ['post' => $post]);
        } else {
            return $response->message();
        }
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        // $validated = $this->validateArticle();
        dd($request->all());
        $validated = $request->validated();

        $validated['slug'] = str_slug($validated['title'], '-');

        // if(!Post::create($validated)){
        //     return redirect()->back()->with('error', 'Error saving the data.');
        // }

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {

        // if(auth()->user()->cant('update', $post)){
        //     return "not allowed";
        // }

        $this->authorize('update', $post);
            

        $validated = $this->validateArticle();

        $validated['slug'] = str_slug($validated['title'], '-');

        if(!$post->update($validated)){
            return redirect()->back()->with('error', 'Error saving the data.');
        }

        return redirect()->route('posts.show', $post->id);
    }


    public function destroy(Post $post)
    {
        if(!$post->delete()){
            return redirect()->back()->with('error', 'Error deleting the item.');
        }
        
        return redirect()->route('posts.index');
    }

    protected function validateArticle()
    {
        return request()->validate([
            'title' => 'required|min:2|max:255',
            'body' => 'required|min:2'
        ]);
    }

}
