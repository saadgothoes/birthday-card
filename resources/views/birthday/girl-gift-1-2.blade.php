<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Collage</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        width: 100%;
        height: 100%;
        font-family: 'Cormorant Garamond', serif;
    }

    body {
        background: linear-gradient(135deg, #fdf0f4 0%, #fce4ee 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        min-height: 100vh;
    }

    .container {
        width: 100%;
        max-width: 800px;
        padding: 40px;
        background: rgba(255, 251, 248, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(196, 112, 154, 0.2);
        backdrop-filter: blur(10px);
    }

    .title {
        text-align: center;
        font-size: clamp(32px, 6vw, 48px);
        color: #c4507a;
        margin-bottom: 12px;
        font-weight: 600;
        letter-spacing: 2px;
    }

    .subtitle {
        text-align: center;
        font-size: clamp(14px, 2vw, 18px);
        color: #a04468;
        margin-bottom: 40px;
        font-style: italic;
    }

    .collage {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 40px;
    }

    .photo-box {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        background: linear-gradient(135deg, #f9a8d4 0%, #fda4af 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(196, 112, 154, 0.2);
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid rgba(196, 112, 154, 0.1);
    }

    .photo-box:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 30px rgba(196, 112, 154, 0.3);
    }

    .photo-box.tall {
        grid-row: span 2;
    }

    .photo-box.wide {
        grid-column: span 2;
    }

    .message {
        text-align: center;
        font-size: clamp(18px, 3vw, 24px);
        color: #8b4e6a;
        line-height: 1.8;
        margin-bottom: 30px;
    }

    .nav-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
    }

    .btn {
        background: #c4507a;
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 60px;
        font-family: 'Cormorant Garamond', serif;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn:hover {
        background: #8b4e6a;
        box-shadow: 0 4px 12px rgba(196, 112, 154, 0.3);
    }

    .btn-prev {
        background: rgba(196, 112, 154, 0.2);
        color: #c4507a;
        border: 1px solid #c4507a;
    }

    .btn-prev:hover {
        background: #c4507a;
        color: white;
    }

    @media (max-width: 768px) {
        .collage {
            grid-template-columns: repeat(2, 1fr);
        }

        .photo-box.tall {
            grid-row: span 1;
        }

        .photo-box.wide {
            grid-column: span 1;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">Our Memories ✨</div>
        <div class="subtitle">A collection of beautiful moments together</div>

        <div class="collage">
            <div class="photo-box" style="background: linear-gradient(135deg, #f472b6 0%, #e8789c 100%);">📷</div>
            <div class="photo-box" style="background: linear-gradient(135deg, #f9a8d4 0%, #f472b6 100%);">💕</div>
            <div class="photo-box tall" style="background: linear-gradient(135deg, #fda4af 0%, #fbcfe8 100%);">🌹</div>
            <div class="photo-box" style="background: linear-gradient(135deg, #fbcfe8 0%, #fda4af 100%);">😍</div>
            <div class="photo-box wide" style="background: linear-gradient(135deg, #f472b6 0%, #fbcfe8 100%);">💑</div>
            <div class="photo-box" style="background: linear-gradient(135deg, #f9a8d4 0%, #fda4af 100%);">🌸</div>
        </div>

        <div class="message">
            Every photo captures a moment of happiness with you. These are the memories I treasure the most, and I can't
            wait to create more with you.
        </div>

        <div class="nav-buttons">
            <button class="btn btn-prev" onclick="goPrev()">← Previous</button>
            <button class="btn" onclick="goNext()">Next Gift →</button>
        </div>
    </div>

    <script>
    function goPrev() {
        window.location.href = '/girl/gift-1/1';
    }

    function goNext() {
        window.location.href = '/girl/gift-1/3';
    }
    </script>
</body>

</html>