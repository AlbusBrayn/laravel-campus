<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $posts = Post::where(['published' => 1])->orderBy('created_at', 'desc')->paginate(10);
        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'title' => 'Başlık',
            'content' => 'İçerik'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;
        $post = Post::create($data);

        return response(['status' => 'success', 'message' => 'Post başarıyla oluşturuldu!', 'data' => new PostResource($post)]);
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'title' => 'Başlık',
            'content' => 'İçerik'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $post = Post::find($id);
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->save();

        return response(['status' => 'success', 'message' => 'Post başarıyla güncellendi!', 'data' => new PostResource($post)]);
    }

    public function like(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $like = Like::where(['user_id' => $request->user()->id, 'post_id' => $post->id, 'is_liked' => true])->first();
        $unlike = Like::where(['user_id' => $request->user()->id, 'post_id' => $post->id, 'is_liked' => false])->first();

        if ($unlike) {
            $unlike->delete();
            $post->dislike = $post->dislike - 1;
            $post->save();
        }

        if ($like) {
            $like->delete();
            $post->like = $post->like - 1;
            $post->save();
            return response(['status' => 'success', 'message' => 'Post beğeni kaldırıldı!']);
        } else {
            Like::create(['user_id' => $request->user()->id, 'post_id' => $post->id]);
            $post->like = $post->like + 1;
            $post->save();
            return response(['status' => 'success', 'message' => 'Post beğenildi!']);
        }
    }

    public function unlike(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $like = Like::where(['user_id' => $request->user()->id, 'post_id' => $post->id, 'is_liked' => true])->first();
        $unlike = Like::where(['user_id' => $request->user()->id, 'post_id' => $post->id, 'is_liked' => false])->first();

        if ($like) {
            $like->delete();
            $post->like = $post->like - 1;
            $post->save();
        }

        if ($unlike) {
            $unlike->delete();
            $post->dislike = $post->dislike - 1;
            $post->save();
            return response(['status' => 'success', 'message' => 'Post beğenmeme kaldırıldı!']);
        } else {
            Like::create(['user_id' => $request->user()->id, 'post_id' => $post->id, 'is_liked' => false]);
            $post->dislike = $post->dislike + 1;
            $post->save();
            return response(['status' => 'success', 'message' => 'Post beğenilmedi!']);
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        return response(['status' => 'success', 'message' => 'Post başarıyla silindi!']);
    }
}
