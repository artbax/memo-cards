var images = ['memo0.png', 'memo1.png', 'memo2.png', 'memo3.png', 'memo4.png'];
$('.draggable').css({'background-image': 'url(../img/' + images[Math.floor(Math.random() * images.length)] + ')'});
