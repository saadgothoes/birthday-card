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
            font-family: 'Bebas Neue', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0f0f 50%, #15080c 100%);
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
            background: rgba(45, 18, 10, 0.9);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(200, 146, 42, 0.2);
            border: 1px solid rgba(200, 146, 42, 0.2);
            backdrop-filter: blur(10px);
        }

        .title {
            text-align: center;
            font-size: clamp(32px, 6vw, 48px);
            color: #d4a850;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            font-size: clamp(14px, 2vw, 18px);
            color: #a07040;
            margin-bottom: 40px;
            letter-spacing: 1px;
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
            background: linear-gradient(135deg, #2d1200 0%, #3d1a00 100%);
            border: 2px solid #c8922a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(200, 146, 42, 0.2);
            cursor: pointer;
            transition: all 0.3s;
        }

        .photo-box:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(200, 146, 42, 0.3);
            border-color: #d4a850;
        }

        .photo-box.tall {
            grid-row: span 2;
        }

        .photo-box.wide {
            grid-column: span 2;
        }

        .message {
            text-align: center;
            font-size: clamp(16px, 2.5vw, 22px);
            color: #b8882a;
            line-height: 1.8;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }

        .nav-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .btn {
            background: #c8922a;
            color: #2d1200;
            border: none;
            padding: 12px 28px;
            border-radius: 60px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .btn:hover {
            background: #d4a850;
            box-shadow: 0 4px 12px rgba(200, 146, 42, 0.3);
        }

        .btn-prev {
            background: transparent;
            color: #c8922a;
            border: 1px solid #c8922a;
        }

        .btn-prev:hover {
            background: #c8922a;
            color: #2d1200;
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
        <div class="title">Our Moments 👑</div>
        <div class="subtitle">Memories worth treasuring forever</div>

        <div class="collage">
            <div class="photo-box" style="background: linear-gradient(135deg, #2d1200 0%, #3d1a00 100%); border-color: #d4a850;">📷</div>
            <div class="photo-box" style="background: linear-gradient(135deg, #3d1a00 0%, #2d1200 100%); border-color: #c8922a;">💪</div>
            <div class="photo-box tall" style="background: linear-gradient(135deg, #251708 0%, #3d1a00 100%); border-color: #d4a850;">👑</div>
            <div class="photo-box" style="background: linear-gradient(135deg, #2d1200 0%, #251708 100%); border-color: #c8922a;">⚔️</div>
            <div class="photo-box wide" style="background: linear-gradient(135deg, #3d1a00 0%, #2d1200 100%); border-color: #d4a850;">💑</div>
            <div class="photo-box" style="background: linear-gradient(135deg, #251708 0%, #2d1200 100%); border-color: #c8922a;">🔥</div>
        </div>

        <div class="message">
            Every moment with you is unforgettable. These memories are treasures I hold close to my heart. You make my life complete, my king.
        </div>

        <div class="nav-buttons">
            <button class="btn btn-prev" onclick="goPrev()">← PREVIOUS</button>
            <button class="btn" onclick="goNext()">NEXT GIFT →</button>
        </div>
    </div>

    <script>
        function goPrev() {
            window.location.href = '/boy/gift-1/1';
        }

        function goNext() {
            window.location.href = '/boy/gift-1/3';
        }
    </script>
</body>

</html>