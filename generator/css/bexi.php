html,body{
    height:100%;
}

.C{
    text-align: center;
}

.R{
    text-align: right;
}

.L{
    text-align: left;
}

.White{
    color: white;
}

.Black{
    color: rgb(51, 51, 51);
}

.Gray{
    color: rgb(129, 129, 129);
}

.Color{
    color: rgb(246, 135, 0);
}

.Light_gray{
    color: rgb(226, 226, 226);
}

h1{
    font-size: 40px !important;
    line-height: normal !important;
    letter-spacing: -0.38px !important;
    font-style: normal !important;
    font-weight: bold !important;
    font-family: 'Montserrat', sans-serif !important;
    width: 100% !important;
}

h2{
    font-size: 30px !important;
    line-height: 48px !important;
    letter-spacing: -0.41px !important;
    font-style: normal !important;
    font-weight: bold !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 5px;
    padding-bottom: 10px;
    width: 100% !important;
}

h3{
    font-size: 20px !important;
    line-height: 42px !important;
    letter-spacing: -0.29px !important;
    font-style: normal !important;
    font-weight: bold !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 5px;
    padding-bottom: 5px;
    width: 100% !important;
}

h4{
    font-size: 18px !important;
    line-height: 32px !important;
    letter-spacing: -0.13px !important;
    font-style: normal !important;
    font-weight: bold !important;
    font-family: 'Montserrat', sans-serif !important;
    width: 100% !important;
}

h5{
    font-size: 16px !important;
    line-height: 28px !important;
    letter-spacing: -0.11px !important;
    font-style: normal !important;
    font-weight: bold !important;
    font-family: 'Montserrat', sans-serif !important;
    width: 100% !important;
}

h6{
    font-size: 14px !important;
    line-height: normal !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    width: 100% !important;
}

.Lead_text{
    font-size: 18px !important;
    line-height: 25px !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 5px;
    padding-bottom: 50px;
    width: 100% !important;
}

a{
    font-size: 16px !important;
    line-height: normal !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 5px;
    padding-bottom: 20px;
    width: 100% ;
}

p{
    font-size: 16px !important;
    line-height: 26px !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 5px;
    padding-bottom: 5px;
    width: 100% !important;
}

.p_small{
    font-size: 14px !important;
    line-height: 26px !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 5px;
    padding-bottom: 5px;
    width: 100% !important;
}

.p_list{
    font-size: 16px !important;
    line-height: 32px !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    width: 100%;
}

.Caption{
    font-size: 14px !important;
    line-height: 22px !important;
    letter-spacing: 1.5px !important;
    font-style: normal !important;
    font-weight: 400 !important;
    font-family: 'Montserrat', sans-serif !important;
    padding-top: 20px;
    padding-bottom: 5px;
    width: 100% !important;
}

.bexi_module{
    padding-top:50px;
    padding-bottom: 50px;
    padding-left: 0px;
    padding-right: 0px;
    width: 100%;
}

.bexi_container{
    padding-left: 32px;
    padding-right: 32px;
    max-width: 1920px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
}

.hero{
    display: flex;
    align-items: center;
    margin-top:auto;
    margin-bottom: auto;
}

.img-background{
    background-image:src='%img|1920|1080|%';
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}

.carousel-indicators{
    bottom: -30px;
}

.bexi_price{

}

/*
[class*="col-"] {
    padding-left: 5px !important;
    padding-right: 5px !important;
}
*/

/*------------ Mobile rules -----------------*/
@media (max-width: 768px) {
    .Order-top{
        Order:-1 !important;
    }
    .Order-bottom{
        Order:1 !important;
    }

    .border_r{
        border-style: solid;
        border-width: 0px !important;
    }

    .half-col{
        width: 50%;
    }

    .change-list li{
        padding-left: 0px !important;
        text-align: center;
        display: list-item !important;
        float:none !important;
    }
}

.bexi_panel
{
	background-color: #CCC;
	padding-left:5%;
}

.color_rec
{
	float: left;
	width: 30px;
	height: 30px;
	border: solid #000;
}
.bexi_input {
    width: 100%;
	margin-top: 12px;
    margin-bottom: 12px;
    padding-left: 20px;
	padding-right: 20px;
	padding-top: 13px;
	padding-bottom: 13px;
    display: inline-block;
    border: 0px solid #ccc;
    background-color: rgba(243, 243, 243, 1.0);
    border-radius: 25px;
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 14.0px;
    color: %color3%;
    line-height: 26.0px;
}

.bexi_title
{
  cursor: text !important;
}

.bexi_subtitle
{
  cursor: text !important;
}

.bexi_text
{
  cursor: text !important;
}


.bexi_icon
{

}

 .bexi_img_full
 {
 	width: 100%;
 	height: auto;
 }

 .vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
}

.v-center {
   display: flex;
   align-items: center;
}

.title2
{
	-ms-transform: rotate(0deg);
	-webkit-transform: rotate(0deg);
	transform: rotate(0deg);
	font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
	font-size: 16.0px;
	color: %color1%;
	text-align: left;
	letter-spacing: 1px;
	line-height: 1.2;
}

.title1 {
    -ms-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 20.0px;
    color: rgba(51, 51, 51, 1.0);
    text-align: left;
    letter-spacing: -0.29px;
    line-height: 1.2;
}

.title3
{
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 30.0px;
    color: rgba(51, 51, 51, 1.0);
    text-align: center;
    letter-spacing: -0.41px;
    line-height: 1.2;
}

.title4
{
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 18.0px;
    color: %color1%;
    text-align: left;
    letter-spacing: -0.13px;
    line-height: 32.0px;

}

.title5
{
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 18.0px;
    color: rgba(51, 51, 51, 1.0);
    text-align: left;
    letter-spacing: -0.13px;
    line-height: 32.0px;

}

.subtitle2 
{
    
    amily: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 14.0px;
    color: rgba(51, 51, 51, 1.0);
    letter-spacing: 1.5px;
    line-height: 22.0px;

}

.subtitle3 {
    -ms-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 14.0px;
    color: rgba(226, 226, 226, 1.0);
    text-align: left;
    line-height: 17.0px;
}

.subtitle4
{
    font-family: "Montserrat",%font1%, Helvetica, Arial, serif;
	font-size: 14.0px;
	color: rgba(51, 51, 51, 1.0);
	text-align: left;
	line-height: 17.0px;
}


.text1 {
    font-family: "Montserrat", "Roboto", Helvetica, Arial, serif;
    font-size: 16.0px;
    color: %color3%;
    text-align: left;
    line-height: 26.0px;
}

.text2 {
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 18.0px;
    color: %color3%;
    text-align: left;
    line-height: 26.0px;
}

.readmore
{
	font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 16.0px;
    color: %color1%;
    text-align: left;
    line-height: 19.0px;

}

.bexi_a
{
	display:block;
}

.menu1
{
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
	font-size: 14.0px;
	color: rgba(51, 51, 51, 1.0);
	text-align: left;
	letter-spacing: 1.5px;
	line-height: 22.0px;

}
.bexi_menu_list 
{
    list-style-type: none;
    padding-inline-start: 0px;
}

.bexi_menu_list_h 
{
    list-style-type: none;
    padding-inline-start: 0px;
}

.bexi_menu_list_h2
{
    list-style-type: none;
    padding-inline-start: 0px;
}
.bexi_menu_list_h3
{
    font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 14.0px;
    color:  %color1%;
    letter-spacing: 1.5px;
    line-height: 22.0px;
    list-style-type: none;
}

.bexi_menu_list_h3 li
{
    padding-left: 30px;
    text-align: center;
    float: left;
}

.bexi_menu_list_h2 li
{
    padding-left: 30px;
    text-align: center;
    float: left;
}

.bexi_menu_list_h li
{
    padding-left: 40px;
    text-align: center;
    display: inline-block;
}

.text_center
{
	text-align: center;
}

.shadow_box
{
    box-shadow: 0px 0px 10px 5px rgba(201,201,201,0.3);
}

.button_rounded
{
	margin-top: 12px;
    margin-bottom: 12px;
    padding-left: 20px;
	padding-right: 20px;
	padding-top: 13px;
    padding-bottom: 13px;
    border-radius: 25px;
	display: inline-block;
	font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
	font-size: 16.0px;
	color: %color1%;
	text-align: center;
    line-height: 19.0px;
    width: 200px;
    height: 50px;
}

.button_menu_rounded
{
	border-radius: 25px;
	padding-left: 80px;
	padding-right: 80px;
	padding-top: 10px;
	padding-bottom: 10px;
	display: inline-block;
	font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
	font-size: 16.0px;
	color: %color1%;
	text-align: center;
	line-height: 19.0px;
}

.button_rounded2
{
	border-radius: 25px;
	padding-left: 10%;
	padding-right: 10%;
	padding-top: 15px;
	padding-bottom: 15px;
	display: inline-block;
	font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
	font-size: 16.0px;
	color: %color1%;
	text-align: center;
	line-height: 19.0px;
}


.button_border1
{
	border-style: solid;
	border-width: 2px;
	border-color: %color1%;

}

.button_fill1
{
	
	background-color: %color1%;
	color: #fff;

}

.button_fill2
{
	background-color: rgba(255, 255, 255, 1.0);
	color: %color1%;
}

.bexi_button
{

}

.border1
{
	border-style: solid;
	border-color: rgba(226, 226, 226, 1.0);
	border-width: 0.5px;
}

.txtbg_number
{
	font-family: "Montserrat","Roboto", Helvetica, Arial, serif;
    font-size: 180.0px;
    color: rgba(244, 244, 244, 1.0);
    text-align: left;
    line-height: 216.0px;

}

.border_r
{
	border-style: solid;
	border-width: 1px;
	border-color: rgba(235, 235, 235, 1.0);
	border-left: none;
	border-top: none;
	border-bottom: none;
}

.bgcolor_fill1
{
	background-color: %color1%;
}

.bgcolor_fill2
{
	background-color: rgba(249, 249, 249, 1.0);
}

.bgcolor_fill3
{
    background-color: rgb(51, 51, 51);
}

.hero_title1 {
    font-family: "Montserrat",%font1%, Helvetica, Arial, serif;
    font-size: 40.0px;
    color: rgba(51, 51, 51, 1.0);
    text-align: left;
    letter-spacing: -0.38px;
    line-height: 48.0px;
}

.button1 {

    background-color: %color1%;
    height: 50px;
    width: 200px;
    margin: 0;
    left: 0px;
    border-radius: 25px;
    font-size: 16.0px;
	color: rgba(255, 255, 255, 1.0);
	line-height: 19.0px;
}

.text_color_1
{
	color: %color1% !important;
}

.form-rounded {
    border-radius: 25px;
}

/* UNSPASH SECTIONS */

.img-wrap {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
  }

  /* Wrap the image and alt text */
  /* Will be added with js */
    .alt-wrap { 
      display: block;
      position: relative;
      margin: 20px;
      color: whitesmoke;
    }

  /* The alt text itself */
  /* Will be added with js */
    .alt-wrap p.bexi_unspash {
      position: absolute;
      opacity: 0; /* hide initially */
      left: 0; right: 0; bottom: 0;
      margin: 0;
      padding: 15px;
      font-size: 14px;
      line-height: 22px;
      background-color: rgba(0,0,0,0.8);
      transition: all 300ms ease;
      transition-delay: 300ms;
    }

  /* Hovering over the wrapper shows its alt p*/
    .alt-wrap:hover > p.bexi_unspash { 
      opacity: 1; 
      transition-delay: 0s;
    }



@media (min-width: 768px) {
.navbar-brand-center
    {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
}


.bexi_input_radio
{

}

/* The container */
.container-radio {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }
  
  /* Hide the browser's default radio button */
  .container-radio input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }
  
  /* Create a custom radio button */
  .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
    border-radius: 50%;
  }
  
  /* On mouse-over, add a grey background color */
  .container-radio:hover input ~ .checkmark {
    background-color: #ccc;
  }
  
  /* When the radio button is checked, add a blue background */
  .container-radio input:checked ~ .checkmark {
    background-color: #ccc;
  }
  
  /* Create the indicator (the dot/circle - hidden when not checked) */
  .checkmark:after {
    content: "";
    position: absolute;
    display: none;
  }
  
  /* Show the indicator (dot/circle) when checked */
  .container-radio input:checked ~ .checkmark:after {
    display: block;
  }
  
  /* Style the indicator (dot/circle) */
  .container-radio .checkmark:after {
       top: 7px;
      left: 7px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: %color1%;
  }