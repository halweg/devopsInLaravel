<?php

namespace App\Exports;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostsExport implements FromQuery, WithHeadings, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return Post::query()
            ->select([
                DB::raw('DATE_FORMAT(publish_at, "%Y-%m-%d %H:%i")'),
                'title',
                'headline',
                'users.name',
                'content',
            ])
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->orderBy('publish_at');
    }

    public function headings(): array
    {
        return [
            'Publish At',
            'Title',
            'Headline',
            'Author',
            'Content',
        ];
    }
}
