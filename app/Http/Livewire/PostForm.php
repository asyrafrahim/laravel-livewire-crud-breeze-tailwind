<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostForm extends Component
{
    use WithPagination;

    public $title;
    public $body;
    public $post_id;

    protected $rules = [
        'title' => 'required',
        'body' => 'required|min:10',
    ];

    public function storePost()
    {
        $this->validate();

        $post = Post::create([
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => auth()->id(),
        ]);
        $this->reset();
    }
    
    public function edit($post)
    {
        $post = Post::find($post);
        $this->post_id = $post->id;
        $this->title = $post->title;
        $this->body = $post->body;
    }

    public function update()
    {
        $post = Post::updateOrCreate(
            [
                'id' => $this->post_id
            ],
            [
                'title' => $this->title,
                'body' => $this->body,
            ]
        );
        $this->reset();
    }

    public function destroy($post)
    {
        Post::destroy($post);
    }

    public function render()
    {
        return view('livewire.post-form', ['posts' => Post::latest()->paginate(10)]);
    }
}
