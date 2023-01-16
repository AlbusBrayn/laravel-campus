<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostReport;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $posts = Post::where(['published' => 1])->with('comments.replies')->orderBy('created_at', $request->sort ?? 'desc')->paginate(10);
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

    public function show(Request $request, $id)
    {
        $post = Post::where(['id' => $id, 'published' => true])->with('comments.replies')->firstOrFail();
        return response(['status' => 'success', 'data' => new PostResource($post)]);
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
            return response(['status' => 'success', 'message' => 'Post beğeni kaldırıldı!', 'like' => $post->like, 'dislike' => $post->dislike]);
        } else {
            Like::create(['user_id' => $request->user()->id, 'post_id' => $post->id]);
            $post->like = $post->like + 1;
            $post->save();
            return response(['status' => 'success', 'message' => 'Post beğenildi!', 'like' => $post->like, 'dislike' => $post->dislike]);
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
            return response(['status' => 'success', 'message' => 'Post beğenmeme kaldırıldı!', 'like' => $post->like, 'dislike' => $post->dislike]);
        } else {
            Like::create(['user_id' => $request->user()->id, 'post_id' => $post->id, 'is_liked' => false]);
            $post->dislike = $post->dislike + 1;
            $post->save();
            return response(['status' => 'success', 'message' => 'Post beğenilmedi!', 'like' => $post->like, 'dislike' => $post->dislike]);
        }
    }

    public function comment(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'body' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);

        $validator->setAttributeNames([
            'body' => 'İçerik',
            'parent_id' => 'Üst Yorum'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;
        $data['post_id'] = $id;
        $comment = Comment::create($data);

        return response(['status' => 'success', 'message' => 'Yorum başarıyla oluşturuldu!']);
    }

    public function commentDelete(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if ($request->user()->id === $comment->user_id) {
            $comment->delete();
            return response(['status' => 'success', 'message' => 'Yorum başarıyla silindi!']);
        } else {
            return response(['status' => 'error', 'message' => 'Bu yorumu silemezsiniz!'], 403);
        }
    }

    public function report(Request $request, $id)
    {
        $user = $request->user();
        $post = Post::findOrFail($id);

        if (PostReport::where(['user_id' => $user->id, 'post_id' => $post->id])->exists()) {
            return response(['status' => 'error', 'message' => 'Bu postu zaten bildirdiniz!'], 400);
        }

        PostReport::create(['user_id' => $user->id, 'post_id' => $post->id]);
        return response(['status' => 'success', 'message' => 'Rapor başarıyla oluşturuldu!']);
    }

    public function search(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'q' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'q' => 'Arama Terimi'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $posts = Post::where(['published' => true])->where('title', 'like', '%' . $data['q'] . '%')->with('comments.replies')->orderBy('created_at', 'desc')->paginate(10);
        return PostResource::collection($posts);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);
        if ($request->user()->id === $post->user_id) {
            $post->delete();
            return response(['status' => 'success', 'message' => 'Post başarıyla silindi!']);
        } else {
            return response(['status' => 'error', 'message' => 'Bu postu silemezsiniz!'], 403);
        }
    }
}
