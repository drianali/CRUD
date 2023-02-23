<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostModel;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        if(Auth()->user()->role == "admin" ||(Auth()->user()->role == "operator"))
        {
            return view('posts.index',[
                'title' => 'Main Page Admin',
                'posts' => PostModel::all()
            ]);

        }else{

            return view('posts.index',[
                'title' => 'Main Page 1',
                'posts' => PostModel::where('user_id', Auth()->user()->id)->get()
            ]);

        }
    }

    public function create()
    {
        $data = [
            'title' => 'view Data'
        ];
        return view('posts.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:155',
            'content' => 'required',
            'status' => 'required'
        ]);

        $post = PostModel::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'slug' => Str::slug($request->title),
            'user_id' => Auth()->user()->id
        ]);

        if ($post) {
            return redirect()
                ->route('post.index')
                ->with([
                    'success' => 'Data Anda Berhasil dibuat'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Data anda gagal dibuat'
                ]);
        }
    }

    public function edit($id)
    {
        $post = PostModel::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:155',
            'content' => 'required',
            'status' => 'required'
        ]);

        $post = PostModel::findOrFail($id);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'slug' => Str::slug($request->title)
        ]);

        if ($post) {
            return redirect()
                ->route('post.index')
                ->with([
                    'success' => 'Data Anda Berhasil Diedit'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Data Anda Gagal Diedit'
                ]);
        }
    }

    public function destroy($id)
    {
        $post = PostModel::findOrFail($id);
        $post->delete();

        if ($post) {
            return redirect()
                ->route('post.index')
                ->with([
                    'success' => 'Data Anda Berhasil Dihapus'
                ]);
        } else {
            return redirect()
                ->route('post.index')
                ->with([
                    'error' => 'Data Anda Gagal Dihapus'
                ]);
        }
    }
}
