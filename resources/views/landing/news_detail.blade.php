@extends('layouts.landing')

@section('title', $post->title . ' - MA Sunan Pandanaran')

@section('content')
<div style="min-height: 100vh; padding-top: 80px; background: #f8fafc;">

    <!-- Hero Banner -->
    <div style="background: linear-gradient(135deg, #064e3b, #047857); padding: 4rem 2rem 3rem; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.78rem; font-weight: 600; margin-bottom: 1.25rem;">
                <i class="bi bi-tag-fill"></i> {{ ucfirst($post->category) }}
            </span>
            <h1 style="color: white; font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; line-height: 1.3; margin-bottom: 1.25rem;">{{ $post->title }}</h1>
            <div style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                <span><i class="bi bi-person-circle"></i> {{ $post->author->name }}</span>
                <span style="margin: 0 0.75rem;">·</span>
                <span><i class="bi bi-calendar3"></i> {{ $post->published_at?->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div style="max-width: 800px; margin: 0 auto; padding: 3rem 2rem;">
        <a href="{{ route('landing') }}#berita" style="display: inline-flex; align-items: center; gap: 0.4rem; color: #047857; font-size: 0.83rem; font-weight: 600; margin-bottom: 2rem; padding: 0.5rem 1rem; background: #ecfdf5; border-radius: 8px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>

        <div style="background: white; border-radius: 20px; padding: 2.5rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.06); line-height: 1.8; color: #334155; font-size: 0.975rem;">
            @if($post->excerpt)
            <p style="font-size: 1.05rem; font-weight: 500; color: #64748b; border-left: 4px solid #047857; padding-left: 1.25rem; margin-bottom: 2rem; font-style: italic;">
                {{ $post->excerpt }}
            </p>
            @endif
            <div style="line-height: 1.9;">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>

        <!-- Related Posts -->
        @if($related->count())
        <div style="margin-top: 3rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b;">Berita Terkait</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                @foreach($related as $r)
                <a href="{{ route('post.show', $r->slug) }}" style="background: white; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 100px; background: linear-gradient(135deg, #047857, #059669); display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        {{ $r->category === 'prestasi' ? '🏆' : ($r->category === 'agenda' ? '📅' : '📰') }}
                    </div>
                    <div style="padding: 1rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #1e293b; line-height: 1.4;">{{ Str::limit($r->title, 70) }}</div>
                        <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 0.5rem;">{{ $r->published_at?->isoFormat('D MMM YYYY') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
