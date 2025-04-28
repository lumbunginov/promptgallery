@extends('layouts.app')

@section('content')
<style>
    .video-container {
        position: relative;
        width: 100%;
        max-width: 100%;
    }
    .custom-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.5);
        border: none;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        transition: opacity 0.2s;
    }
    .custom-play-btn svg {
        width: 40px;
        height: 40px;
        fill: white;
    }
    .custom-play-btn.hide {
        opacity: 0;
        pointer-events: none;
    }
    video {
        width: 100%;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: #000;
    }
</style>
<div class="flex flex-col items-center justify-center min-h-screen py-8 bg-white">
    <h1 class="text-3xl font-bold mb-8 text-center">Tutorial Rara AI</h1>
    <div class="w-full max-w-md px-8">
        <div class="video-container">
            <video id="tutorialVideo" poster="" preload="metadata">
                <source src="{{ asset('storage/tutorial.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <button id="customPlayBtn" class="custom-play-btn" aria-label="Play">
                <svg viewBox="0 0 64 64"><circle cx="32" cy="32" r="32" fill="rgba(0,0,0,0.3)"/><polygon points="26,18 50,32 26,46"/></svg>
            </button>
        </div>
    </div>
</div>
<script>
    const video = document.getElementById('tutorialVideo');
    const playBtn = document.getElementById('customPlayBtn');
    playBtn.addEventListener('click', function() {
        video.play();
        playBtn.classList.add('hide');
        video.setAttribute('controls', 'controls');
    });
    video.addEventListener('pause', function() {
        playBtn.classList.remove('hide');
        video.removeAttribute('controls');
    });
    video.addEventListener('play', function() {
        playBtn.classList.add('hide');
        video.setAttribute('controls', 'controls');
    });
</script>
@endsection 