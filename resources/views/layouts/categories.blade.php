<style>
    
.category_container {
	max-width: 110rem;
	padding: 1.5rem;
}

.category_cards {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(20rem, 1fr));
	grid-gap: 1.5rem;
}

.category_card {
	background: #262525;
	color: #fff;
	padding: 1.5rem;
	overflow: hidden;
	border-radius: 0.8rem;
}

.category_card-title-large {
	font-family: fantasy, sans-serif;
	font-size: 10rem;
	letter-spacing: 0.5rem;
	color: #1d1d1d;
    user-select: none;
	transform: translateX(7rem);
	transition: transform 2.5s;
}

.category_card-title-small {
	margin-bottom: 1rem;
}

.category_card-description {
	font-size: 1.4rem;
	line-height: 1.5;
}

.category_card-cta {
	display: inline-block;
	width: 3.5rem;
	height: 3.5rem;
	border-radius: 50%;
	display: grid;
	place-items: center;
	margin-left: auto;
}

.category_card:hover .card-cta {
	background-color: #262525;
}

.category_card:hover .card-title-large {
	transform: translateX(-115%);
	color: #fff;
}

.category_card.twitter:hover {
	background-color: #1da1f2;
}

.category_card.instagram:hover {
	background: radial-gradient(
		at 20% 128%,
		#feda78 20%,
		#e23467 60%,
		#ac2bb3 90%
	);
}

.category_card.facebook:hover {
	background-color: #4267b2;
}

.category_card.youtube:hover {
	background-color: #ff074f;
}
</style>
