<style>
.container .row .col-lg-4 {
    display: flex;
    justify-content: center;
}

.card {
    position: relative;
    padding: 0;
    margin: 0 !important;
    border-radius: 20px;
    overflow: hidden;
    /* max-width: 280px;
    max-height: 440px; */
    cursor: pointer;
    border: none;
    /* box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); */
    background: rgba(255, 255, 255, 0.2);
    text-align: left;

}

.card .card-image {
    width: 100%;
    max-height: 640px;
}

.card .card-image img {
    width: 100%;
    max-height: 640px;
    object-fit: cover;
    background-image: radial-gradient(#ffffff, #e1e1e1);
}

.card .card-content {
    position: absolute;
    bottom: -180px;
    color: #212121;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(20px);
    min-height: 120px;
    width: 100%;
    transition: bottom .4s ease-in;
    box-shadow: 0 -10px 10px rgba(255, 255, 255, 0.1);
    /* border-top: 12px solid rgba(255, 255, 255, 0.2); */
}

.card:hover .card-content {
    bottom: 0px;
}

.card:hover .card-content h4,
.card:hover .card-content h5 {
    transform: translateY(20px);
    opacity: 1;
}

.card .card-content h4{
    font-size: 2.1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    transition: 0.8s;
    font-weight: 400;
    opacity: 0;
    transform: translateY(-40px);
    transition-delay: 0.2s;
}
/* .card .card-content h5 {
    font-size: .1rem;
    text-transform: lowercase;
    letter-spacing: 3px;
    text-align: center;
    transition: 0.8s;
    font-weight: 400;
    opacity: 0;
    transform: translateY(-40px);
    transition-delay: 0.2s;
} */

.card .card-content h5 {
    transition: 0.5s;
    font-weight: 200;
    font-size: 1rem;
    letter-spacing: 0px;
}

/* .card .card-content .social-icons {
    list-style: none;
    padding: 0;
} */


/* .card .card-content .social-icons li {
    margin: 10px;
    transition: 0.5s;
    transition-delay: calc(0.15s * var(--i));
    transform: translateY(50px);
} */


/* .card:hover .card-content .social-icons li {
    transform: translateY(20px);
}

.card .card-content .social-icons li a {
    color: #212121;
}

.card .card-content .social-icons li a span {
    font-size: 1.3rem;
} */
</style>