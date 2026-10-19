<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\MediaUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->latest()->get();

        return view('admin.articles.index', compact('articles'));
    }

    public function store(Request $request, MediaUploadService $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'thumbnail_url' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ]);

        $thumb = $uploader->handleMedia(
            $request->file('thumbnail_file'),
            $request->input('compressed_thumbnail') ?: $request->input('thumbnail_url'),
            'articles'
        ) ?: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80';

        $slug = Str::slug($validated['title']);
        if (Article::query()->where('slug', $slug)->exists()) {
            $slug .= '-'.strtolower(Str::random(4));
        }

        Article::query()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'thumbnail' => $thumb,
            'author_name' => 'Tim Pakar Cyber Gaming Store',
            'read_time_minutes' => max(3, (int) ceil(str_word_count(strip_tags($validated['content'])) / 180)),
            'meta_title' => $validated['meta_title'] ?: $validated['title'].' - Cyber Gaming Store',
            'meta_description' => $validated['meta_description'] ?: $validated['excerpt'],
            'meta_keywords' => $validated['meta_keywords'] ?: 'cyber gaming store, laptop gaming second, sewa laptop, service laptop',
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Artikel SEO berhasil diterbitkan dan otomatis masuk ke Sitemap Google!');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
