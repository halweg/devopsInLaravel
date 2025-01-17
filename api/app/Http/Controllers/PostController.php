<?php

namespace App\Http\Controllers;

use App\Exports\PostsExport;
use App\Http\Requests\UpsertPostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\NotifyUserAboutCompletedExport;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(
            Post::with('author')->orderBy('publish_at')->get(),
        );
    }

    public function store(UpsertPostRequest $request)
    {
        $post = $request->user()->posts()->create($request->except('cover_photo'));

        $this->storeCoverPhoto($request, $post);

        return PostResource::make($post);
    }

    public function show(Post $post)
    {
        if (!$post->is_published) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return PostResource::make($post);
    }

    public function update(UpsertPostRequest $request, Post $post)
    {
        $post->fill([
            ...$request->validated(),
            'author_id' => $request->user()->id,
        ]);

        $post->save();

        $this->storeCoverPhoto($request, $post);

        return response()->noContent();
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }

    public function export(Request $request)
    {
        $date = now()->format('Ymd_His');

        $path = "public/exports/posts-$date.xlsx";

        $link = url()->to('/') . Storage::url($path);

        (new PostsExport())->store($path)->chain([
            new NotifyUserAboutCompletedExport($request->user(), $link),
        ]);

        return response('', Response::HTTP_ACCEPTED);
    }

    public function publish(Post $post)
    {
        $post->publish();

        return response()->noContent();
    }

    private function storeCoverPhoto(UpsertPostRequest $request, Post $post)
    {
        $file = $request->file('cover_photo');

        if ($file) {
            $filename = Str::slug($post->title, '_') . '-' . $post->id . '.' . $file->extension();

            $path = storage_path('app/public/post_cover_photos');

            $file->move($path, $filename);

            $post->cover_photo_path = $path . '/' . $filename;

            $post->save();
        }
    }
}
