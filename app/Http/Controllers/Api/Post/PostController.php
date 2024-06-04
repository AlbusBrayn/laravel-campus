<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Like;
use App\Models\LikeComment;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\PostTitle;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class PostController extends Controller
{

    public array $sort = [
        "highest_like",
        "lowest_like",
        "newest",
        "oldest"
    ];

    public function index(Request $request)
    {
        $blockedIds = $request->user()->getBlockedFriendships()->pluck('recipient_id')->toArray();
        if (in_array($request->user()->id, $blockedIds)) {
            $blockedIds = array_diff($blockedIds, [$request->user()->id]);
        }

        $sort = $request->sort;
        if (!in_array($sort, $this->sort)) {
            $sort = "newest";
        }

        switch ($sort) {
            case "highest_like":
                $posts = Post::where(['published' => true, 'is_active' => true])->whereNotIn('user_id', $blockedIds)->with('comments.replies')->orderBy('like', 'desc')->paginate(10);
                break;
            case "lowest_like":
                $posts = Post::where(['published' => true, 'is_active' => true])->whereNotIn('user_id', $blockedIds)->with('comments.replies')->orderBy('like', 'asc')->paginate(10);
                break;
            case "newest":
                $posts = Post::where(['published' => true, 'is_active' => true])->whereNotIn('user_id', $blockedIds)->with('comments.replies')->orderBy('created_at', 'desc')->paginate(10);
                break;
            case "oldest":
                $posts = Post::where(['published' => true, 'is_active' => true])->whereNotIn('user_id', $blockedIds)->with('comments.replies')->orderBy('created_at', 'asc')->paginate(10);
                break;
            default:
                return false;
        }

        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|int',
            'short_content' => 'required|string',
            'content' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'title' => 'Konu ',
            'short_content' => 'Başlık',
            'content' => 'İçerik'
        ]);

        $user = $request->user();

        /*
        $commentCount = Comment::where(['user_id' => $user->id])->count();
        if ($commentCount < 10) {
            return response(['status' => 'error', 'message' => 'Konu açmak için 10 adet yorum yapmalısınız!'], 400);
        }
        */

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        if (!PostTitle::where(['id' => $request->title])->exists()) {
            return response(['status' => 'error', 'message' => 'error!', 'data' => ['title' => ['Başlık bulunamadı.']]], 400);
        }

        $data = $validator->validated();
        $data['user_id'] = $user->id;
        $post = Post::create($data);

        return response(['status' => 'success', 'message' => 'Post başarıyla oluşturuldu!', 'data' => new PostResource($post)]);
    }

    public function titleList(Request $request)
    {
        $titles = PostTitle::where(['is_active' => true])->get();

        return response(['status' => 'success', 'data' => $titles]);
    }

    public function show(Request $request, $id)
    {
        $post = Post::where(['id' => $id, 'published' => true, 'is_active' => true])->with('comments.replies')->firstOrFail();
        return response(['status' => 'success', 'data' => new PostResource($post)]);
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string',
            'short_content' => 'required|string',
            'content' => 'required|string',
        ]);

        $validator->setAttributeNames([
            'title' => 'Konu',
            'short_content' => 'Başlık',
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

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
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

            if ($post->user->device_id) {
                FirebaseService::sendNotification($post->user->device_id, 'Konun beğenildi!', $request->user()->name . ' adlı kullanıcı ' . $post->title . ' başlıklı konunuzu beğendi!');
            }

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

    public function likeComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $like = LikeComment::where(['user_id' => $request->user()->id, 'comment_id' => $comment->id, 'is_liked' => true])->first();
        $unlike = LikeComment::where(['user_id' => $request->user()->id, 'comment_id' => $comment->id, 'is_liked' => false])->first();

        if ($unlike) {
            $unlike->delete();
            $comment->dislike_count = $comment->dislike_count - 1;
            $comment->save();
        }

        if ($like) {
            $like->delete();
            $comment->like_count = $comment->like_count - 1;
            $comment->save();
            return response(['status' => 'success', 'message' => 'Yorum beğenisi kaldırıldı!', 'like' => $comment->like_count, 'dislike' => $comment->dislike_count]);
        } else {
            LikeComment::create(['user_id' => $request->user()->id, 'comment_id' => $comment->id]);
            $comment->like_count = $comment->like_count + 1;
            $comment->save();
            return response(['status' => 'success', 'message' => 'Yorum beğenildi!', 'like' => $comment->like_count, 'dislike' => $comment->dislike_count]);
        }
    }

    public function unlikeComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $like = LikeComment::where(['user_id' => $request->user()->id, 'comment_id' => $comment->id, 'is_liked' => true])->first();
        $unlike = LikeComment::where(['user_id' => $request->user()->id, 'comment_id' => $comment->id, 'is_liked' => false])->first();

        if ($like) {
            $like->delete();
            $comment->like_count = $comment->like_count - 1;
            $comment->save();
        }

        if ($unlike) {
            $unlike->delete();
            $comment->dislike_count = $comment->dislike_count - 1;
            $comment->save();
            return response(['status' => 'success', 'message' => 'Yorum beğenmeme kaldırıldı!', 'like' => $comment->like_count, 'dislike' => $comment->dislike_count]);
        } else {
            LikeComment::create(['user_id' => $request->user()->id, 'comment_id' => $comment->id, 'is_liked' => false]);
            $comment->dislike_count = $comment->dislike_count + 1;
            $comment->save();
            return response(['status' => 'success', 'message' => 'Yorum beğenilmedi!', 'like' => $comment->like_count, 'dislike' => $comment->dislike_count]);
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

    public function reportComment(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'reason_id' => 'required|integer|in:1,2,3,4,5,6,7,8,9',
        ]);

        $validator->setAttributeNames([
            'reason_id' => 'Sebep',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Hata.', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $comment = Comment::findOrFail($id);

        if (CommentReport::where(['user_id' => $user->id, 'comment_id' => $comment->id])->exists()) {
            return response(['status' => 'error', 'message' => 'Bu yorumu zaten bildirdiniz!'], 400);
        }

        if ($user->id === $comment->user_id) {
            return response(['status' => 'error', 'message' => 'Kendinize ait yorumu bildiremezsiniz!'], 400);
        }

        CommentReport::create(['user_id' => $user->id, 'comment_id' => $comment->id, 'reason_id' => $request->reason_id]);
        return response(['status' => 'success', 'message' => 'Yorum başarıyla bildirildi!']);
    }

    public function report(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'reason_id' => 'required|integer|in:1,2,3,4,5,6,7,8,9',
        ]);

        $validator->setAttributeNames([
            'reason_id' => 'Sebep',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'Hata.', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $post = Post::findOrFail($id);

        if (PostReport::where(['user_id' => $user->id, 'post_id' => $post->id])->exists()) {
            return response(['status' => 'error', 'message' => 'Bu postu zaten bildirdiniz!'], 400);
        }

        if ($user->id === $post->user_id) {
            return response(['status' => 'error', 'message' => 'Kendi gönderini bildiremezsin!'], 400);
        }

        PostReport::create(['user_id' => $user->id, 'post_id' => $post->id, 'reason_id' => $request->reason_id]);
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
        $posts = Post::where(['published' => true, 'is_active' => true])->where('content', 'like', '%' . $data['q'] . '%')->with('comments.replies')->orderBy('created_at', 'desc')->paginate(10);
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
