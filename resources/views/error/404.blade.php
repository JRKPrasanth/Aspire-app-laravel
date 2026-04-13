<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>
<style type="text/css">

nav a {
  border: none;
}

 * {
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}
body{
    background: url(../images/error_images/b2.png);
    background-size: cover;
}


#notfound {
    position: relative;
    height: 100vh;
    background: #f6f6f6
}

#notfound .notfound {
    position: absolute;
    left: 50%;
    top: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%)
}

.notfound {
  /*cursor: not-allowed;*/
    max-width: 767px;
    width: 100%;
    line-height: 1.4;
    padding: 110px 40px;
    text-align: center;
    background: #fff;
    /*-webkit-box-shadow: 0 15px 15px -10px rgba(0, 0, 0, .1);
    box-shadow: 0 15px 15px -10px rgba(0, 0, 0, .1)*/
}

.notfound .notfound-404 {
    position: relative;
    height: 180px
}

.notfound .notfound-404 h1 {
    font-family: roboto, sans-serif;
    position: absolute;
    left: 50%;
    top: 75%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    font-size: 105px;
    font-weight: 700;
    margin: 0;
    color: #262626;
    text-transform: uppercase
}

.notfound .notfound-404 h1>span {
    color: #00b7ff
}

.notfound h2 {
    font-family: roboto, sans-serif;
    font-size: 22px;
    font-weight: 400;
    text-transform: uppercase;
    color: #151515;
    margin-top: 0;
    margin-bottom: 25px
}

.notfound .notfound-search {
    position: relative;
    max-width: 320px;
    width: 100%;
    margin: auto
}

.notfound .notfound-search>input {
    font-family: roboto, sans-serif;
    width: 100%;
    height: 50px;
    padding: 3px 65px 3px 30px;
    color: #151515;
    font-size: 16px;
    background: 0 0;
    border: 2px solid #c5c5c5;
    border-radius: 40px;
    -webkit-transition: .2s all;
    transition: .2s all
}

.notfound .notfound-search>input:focus {
    border-color: #00b7ff
}

.notfound .notfound-search>button {
    position: absolute;
    right: 15px;
    top: 5px;
    width: 40px;
    height: 40px;
    text-align: center;
    border: none;
    background: 0 0;
    padding: 0;
    cursor: pointer
}

.notfound .notfound-search>button>span {
    width: 15px;
    height: 15px;
    position: absolute;
    left: 50%;
    top: 50%;
    -webkit-transform: translate(-50%, -50%) rotate(-45deg);
    -ms-transform: translate(-50%, -50%) rotate(-45deg);
    transform: translate(-50%, -50%) rotate(-45deg);
    margin-left: -3px
}

.notfound .notfound-search>button>span:after {
    position: absolute;
    content: '';
    width: 10px;
    height: 10px;
    left: 0;
    top: 0;
    border-radius: 50%;
    border: 4px solid #c5c5c5;
    -webkit-transition: .2s all;
    transition: .2s all
}

.notfound-search>button>span:before {
    position: absolute;
    content: '';
    width: 4px;
    height: 10px;
    left: 7px;
    top: 17px;
    border-radius: 2px;
    background: #c5c5c5;
    -webkit-transition: .2s all;
    transition: .2s all
}

.notfound .notfound-search>button:hover>span:after {
    border-color: #00b7ff
}

.notfound .notfound-search>button:hover>span:before {
    background-color: #00b7ff
}

@media only screen and (max-width:767px) {
    .notfound h2 {
        font-size: 18px
    }
}

@media only screen and (max-width:480px) {
    .notfound .notfound-404 h1 {
        font-size: 141px
    }
}
@keyframes blink {
    0%   {opacity: 0}
    49%  {opacity: 0.7}
    50%  {opacity: 1}
    100% {opacity: 1}
}

.blink {
   animation-name: blink;
    animation-duration: 1s;
   animation-iteration-count: infinite;
}
a {
  color: #000;
  text-decoration: none;
  font-weight: bold;
  display: inline-block;

 padding: 6px;
  border-radius: 5px;
  border: 2px solid #00b7ff;
  transition: all .5s ease;
}

a:hover {
  color: #fff;
  background: #00b7ff;
  border: 2px solid #00b7ff;
  width: 132px;
}
.notfound .contact-btn {

    color: rgba(0,0,0,.9);
}
.notfound .contact-btn:hover {
    background: none;

}
</style>


<div class="container">

  <div id="notfound" style="background:none;">
<div class="notfound" style="">
<div class="notfound-404">
<img src="{{asset('images/error_images/sign.png')}}" width="100" height="100" style="margin-top: -1%;">
<h1>4<span>0</span>4</h1>
</div>
<h2 style="text-shadow: 0 2px 2px rgba(0,0,0,0.6);margin-top: 5px;
    margin-bottom: 11px;">SORRY</h2>
<h2  style="color: #262626">THE PAGE YOU REQUESTED COULD NOT FOUND</h2>
 <!-- <span class="blink">_</span></h2> -->
<p style="cursor: pointer;"><a href="{{URL::to('home')}}">Home </a></p>
</div>
</div>
</div>

</body>
</html>
