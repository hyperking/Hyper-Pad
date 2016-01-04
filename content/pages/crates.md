title:Old crates game
---

<!-- HTML version -->



<!-- Style goes here -->
<style type="text/css">
#canvas{
width:868px;
height:546px;
background-color:#fb9e0b;
margin:0 auto;
border:1px solid black;
position: relative;
}
#panel{
width:279px;
height:546px;
background-color:white;
float:right;
}

h1.title{
	font-family:"Hanotate TC", Arial;
	font-size:30px;
	text-align:center;
}
#game {
    position: absolute;
    bottom: 0;
    width: 573px;
    margin: 0 auto;
}

.crate {
    width: 50px;
    height: 50px;
    background: yellow;
    float: left;
    border: 1px solid yellowgreen;
}

[data-color="blue"] {
    background: blue;
}

[data-color="red"] {
    background: red;
}
.crate.active {
    background-color: black;
}
.crate.destroy {
    opacity: .3;
}
</style>
<!-- end styles -->




<div id="canvas">
<div id="game">
    
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="blue"></div>
<div class="crate" data-color="blue"></div>
<div class="crate" data-color="blue"></div>
<div class="crate" data-color="blue"></div>
<div class="crate" data-color="blue"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="blue"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="red"></div>
<div class="crate" data-color="green"></div>
<div class="crate" data-color="red"></div>
</div>
<div id="panel">
<h1 class="title">CRATES</h1>

</div>
</div>
<!-- HTML version -->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<script>
$('.crate').on('click', function(){
	
	$('.active').removeClass('active');
	$(this).addClass('active');
	var pos = $(this).offset();
	var width = $(this).width();
	var cratecolor = $(this).data('color');

	var leftCrate = {"left": (pos.left - width ), "top": pos.top};
	var rightCrate = {"left": (pos.left + width), "top": pos.top};
	var topCrate = {"left": pos.left,"top": (pos.top - width)};
	var bottomCrate = {"left": pos.left, "top": (pos.top+width)};
	var topLeftCrate = {"left":leftCrate.left,"top":(leftCrate.top - (width))}
	var bottomLeftCrate = {"left":leftCrate.left,"top":(leftCrate.top + (width))}
	var topRightCrate = {"left":(rightCrate.left- width),"top":rightCrate.top}
	var bottomRightCrate = {"left":(rightCrate.left- width),"top":rightCrate.top}


	console.log('current is '+pos.left,pos.top);
	console.log('topright is ',rightCrate);
	var elem = document.elementFromPoint(rightCrate.left, rightCrate.top);
	$(elem).addClass('destroy');

	// $('.crate').each(function(){
	// console.log(elem);

	// if ($(this).offset() == elem ){

	// $(this).addClass('destroy');

	// }
	// });



});
</script>