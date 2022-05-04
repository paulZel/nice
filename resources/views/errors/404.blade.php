<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404</title>
</head>
<body>

<style>
    @import "https://fonts.googleapis.com/css?family=Roboto:300,900";
    html,body{margin:0;overflow:hidden;background:#0A0A0A}
    canvas{-webkit-animation:rotate 30s linear infinite;animation:rotate 30s linear infinite}
    .content{margin:auto;position:absolute;top:0;left:0;bottom:0;right:0;width:50vw;height:50vh;text-align:center}
    h1,h2{margin:0;color:#fff;text-shadow:0 0 100px #00f,0 3px 0 rgba(50,0,100,0.8);-webkit-filter:url(#drop-shadow);filter:url(#drop-shadow);font-family:Roboto;font-weight:300}
    h1{font-size:30vh}
    h2{font-size:7vh}
    .black-hole{font-weight:900;text-shadow:0 0 50px #8E57FF;-webkit-backface-visibility:hidden;backface-visibility:hidden;position:relative;-webkit-animation:pulse 1s ease infinite,pulse2 1s ease infinite;animation:pulse 1s ease infinite,pulse2 1s ease infinite}
    .black-hole:after{content:"0";position:absolute;top:6%;left:5%;color:#f9f;font-size:90%;-webkit-filter:blur(5px);filter:blur(5px)}
    .black-hole:before{content:"0";position:absolute;top:0;left:0;color:#f0f;-webkit-filter:blur(5px);filter:blur(5px)}
    @-webkit-keyframes pulse {
        0%{-webkit-transform:scale(0.95) rotateZ(-3deg) translateZ(0);transform:scale(0.95) rotateZ(-3deg) translateZ(0)}
        50%{-webkit-transform:scale(1.05) rotateZ(3deg) translateZ(0);transform:scale(1.05) rotateZ(3deg) translateZ(0)}
        100%{-webkit-transform:scale(0.95) rotateZ(-3deg) translateZ(0);transform:scale(0.95) rotateZ(-3deg) translateZ(0)}
    }
    @keyframes pulse {
        0%{-webkit-transform:scale(0.95) rotateZ(-3deg) translateZ(0);transform:scale(0.95) rotateZ(-3deg) translateZ(0)}
        50%{-webkit-transform:scale(1.05) rotateZ(3deg) translateZ(0);transform:scale(1.05) rotateZ(3deg) translateZ(0)}
        100%{-webkit-transform:scale(0.95) rotateZ(-3deg) translateZ(0);transform:scale(0.95) rotateZ(-3deg) translateZ(0)}
    }
    @-webkit-keyframes pulse2 {
        0%{text-shadow:0 0 50px #8E57FF;-webkit-filter:hue-rotate(0deg);filter:hue-rotate(0deg)}
        50%{text-shadow:0 0 60px #8000FF;-webkit-filter:hue-rotate(30deg);filter:hue-rotate(30deg)}
        100%{text-shadow:0 0 50px #8E57FF;-webkit-filter:hue-rotate(-30deg);filter:hue-rotate(-30deg)}
    }
    @keyframes pulse2 {
        0%{text-shadow:0 0 50px #8E57FF;-webkit-filter:hue-rotate(0deg);filter:hue-rotate(0deg)}
        50%{text-shadow:0 0 60px #8000FF;-webkit-filter:hue-rotate(30deg);filter:hue-rotate(30deg)}
        100%{text-shadow:0 0 50px #8E57FF;-webkit-filter:hue-rotate(-30deg);filter:hue-rotate(-30deg)}
    }
    @-webkit-keyframes rotate {
        to{-webkit-transform:rotateZ(360deg);transform:rotateZ(360deg)}
    }
    @keyframes rotate {
        to{-webkit-transform:rotateZ(360deg);transform:rotateZ(360deg)}
    }
</style>

<canvas id="c"></canvas>

<div class="content">
    <h1>4<span class="black-hole">0</span>4</h1>
    <h2>Страница не найдена.</h2>
</div>

<svg height="0">
    <filter id="drop-shadow">
        <feMorphology operator="dilate" radius="10" in="SourceAlpha" result="dilated" />

        <feGaussianBlur stdDeviation="10" in="dilated" result="blurred" />

        <feMerge>
            <feMergeNode/>
            <feMergeNode in="SourceGraphic" />
        </feMerge>
    </filter>
</svg>

<script>
    var c = document.getElementById("c");
    var ctx = c.getContext("2d");
    var size = Math.max(window.innerWidth, window.innerHeight);
    c.width = size;
    c.height = size;

    var startCircles = 100;
    var limit = 100;
    var circles = [];
    var center = {};

    var Circle = function(x, y) {
        this.x = x;
        this.y = y;
        this.angle = 0;
        this.radius = rand(2, 10);
        this.shade = rand(10, 100) / 100;
    }

    Circle.prototype.end = function() {
        circles.splice(circles.indexOf(this), 1);
    };

    init();

    function init() {
        limit = Math.max(startCircles, limit);
        circles = [];
        ctx.fillStyle = "#0A0A0A";
        ctx.fillRect(0, 0, c.width, c.height);
        for (var i = 0; i < startCircles; i++) {
            setTimeout(function() {
                var x = rand(0, c.width);
                var y = rand(0, c.height);
                circles.push(new Circle(x, y));
            }, startCircles * i);
        }

        var hole = document.getElementsByClassName("black-hole")[0].getBoundingClientRect();
        center.x = hole.left + hole.width / 2;
        center.y = hole.top + hole.height / 2;
        animate();
    }

    function animate() {
        ctx.fillStyle = "rgba(10,10,10,0.5)";
        ctx.fillRect(0, 0, c.width, c.height);

        var i = circles.length;
        var gvx, gvy;
        while (i--) {
            var circle = circles[i];
            circle.radius -= 0.05;
            circle.radius = Math.abs(circle.radius);

            vx = (center.x - circle.x) / Math.abs(circle.y) * 2;
            vy = (center.y - circle.y) / Math.abs(circle.x) * 2;

            circle.x += vx;
            circle.y += vy;

            if (circle.radius <= 0.1) {
                circle.end();
                var x = rand(0, c.width);
                var y = rand(0, c.height);
                circles.push(new Circle(x, y));
            }

            ctx.beginPath();
            ctx.arc(circle.x, circle.y, circle.radius, 0, 2 * Math.PI, false);
            ctx.fillStyle = "rgba(0,0,255," + circle.shade + ")";
            ctx.fill();
            ctx.closePath();
        }

        if (circles.length > limit) {
            circles.shift();
        }

        requestAnimationFrame(animate);
    }

    function rand(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    window.addEventListener("click", function(e) {
        circles.push(new Circle(e.clientX, e.clientY));
    })
</script>
</body>
</html>