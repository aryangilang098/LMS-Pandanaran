<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\ContactMessage;

class HomeController extends Controller
{
    public function index()
    {
        $featured_posts = Post::whereNotNull('published_at')
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $recent_posts = Post::whereNotNull('published_at')
            ->latest('published_at')
            ->take(6)
            ->get();

        $agenda = Post::whereNotNull('published_at')
            ->where('category', 'agenda')
            ->whereNotNull('event_date')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(4)
            ->get();

        $prestasi = Post::whereNotNull('published_at')
            ->where('category', 'prestasi')
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('landing.index', compact(
            'featured_posts', 'recent_posts', 'agenda', 'prestasi'
        ));
    }

    public function show($slug)
    {
        $post = Post::whereNotNull('published_at')->where('slug', $slug)->firstOrFail();
        $related = Post::whereNotNull('published_at')
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();
        return view('landing.news_detail', compact('post', 'related'));
    }

    public function contact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:20',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'subject', 'message'));

        return back()->with('success', 'Pesan Anda berhasil terkirim! Kami akan menghubungi Anda segera.');
    }
}
