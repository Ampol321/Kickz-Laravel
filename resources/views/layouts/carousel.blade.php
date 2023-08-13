<style>
    #slider {
	overflow: hidden;
    margin-bottom: 30px;
}

@keyframes slider {
	0% { left: 0; }
	30% { left: 0; }
	33% { left: -100%; }
	63% { left: -100%; }
	66% { left: -200%; }
	95% { left: -200%; }
	100% { left: 0; }
}
#slider figure {
	width:300%;
	position: relative;
	animation: 15s slider infinite;
}

#slider figure:hover {

}

#slider figure img {
	width: 33.333333333%;
	height : 70%;
	float: left;
}
</style>