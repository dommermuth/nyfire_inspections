const pubdomain = 'https://kp.wbdvlpmnt.com';
const themeURI = pubdomain + '/wp-content/themes/kaiser_permanente_2021/';
const mapImg = themeURI + '/assets/images/Blank_map_BG.png';
const hospitalImage = themeURI + '/assets/images/Hospital-Icon.png';
const medCenterImg = themeURI + '/assets/images/Medical-Center-icon.png';
const medCenterUCImg = themeURI + '/assets/images/Urgent-Care.png';
const medCenterAUCImg = themeURI + '/assets/images/UC-24-7-ICON_1.png';
const font1 = themeURI + '/assets/fonts/Gotham-Light_Web.woff2';
const font2 = themeURI + '/assets/fonts/Gotham-Light_Web.woff';
const font3 = themeURI + '/assets/fonts/Gotham-Book_Web.woff2';
const font4 = themeURI + '/assets/fonts/Gotham-Book_Web.woff';
const font5 = themeURI + '/assets/fonts/Gotham-Medium_Web.woff2';
const font6 = themeURI + '/assets/fonts/Gotham-Medium_Web.woff';
const font7 = themeURI + '/assets/fonts/Gotham-Bold_Web.woff2';
const font8 = themeURI + '/assets/fonts/Gotham-Bold_Web.woff';

//map items
//x percent, y percent, name, street, city-state-zip, link, comming soon
const hospitals_ar = [
    [75, 17, "Greater Baltimore Medical Center", "6701 North Charles St.", "Baltimore", "MD", "21204", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/greater-baltimore-medical-center/", 0],
    [43, 24, "Holy Cross Germantown Hospital", "19801 Observation Dr.", "Germantown", "MD", "20876", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/holy-cross-germantown-hospital/", 0],
    [69, 29, "Baltimore Washington Medical Center", "301 Hospital Dr.", "Glen Burnie", "MD", "21061", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/baltimore-washington-medical-center/", 0],
    [47, 33, "Suburban Hospital", "8600 Old Georgetown Rd.", "Bethesda", "MD", "20814", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/suburban-hospital/", 0],
    [52, 34, "Holy Cross Hospital", "1500 Forest Glen Rd.", "Silver Spring", "MD", "20910", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/holy-cross-hospital/", 0],
    [73, 34, "Anne Arundel Medical Center", "2001 Medical Parkway", "Annapolis", "MD", "21401", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/anne-arundel-medical-center/", 0],
    [42, 40, "Reston Medical Center", "1890 Metro Center Drive", "Reston", "VA", "20190", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/reston-medical-center-100404"],
    [54, 39, "Children’s National Health System", "111 Michigan Ave., N.W.", "Washington", "DC", "20010", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/childrens-national-health-system/", 0],
    [55, 42, "MedStar Washington Hospital Center", "110 Irving St., N.W.", "Washington", "DC", "20010", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/medstar-washington-hospital-center/", 0],
    [49, 48, "Virginia Hospital Center", "1701 N. George Mason Dr.", "Arlington", "VA", "22205", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/virginia-hospital-center/", 0],
    [35, 69, "Stafford Hospital", "101 Hospital Center Blvd.", "Stafford", "VA", "22554", "https://premierhospitals.kaiserpermanente.org/premier-hospitals-in-md-dc-va/stafford-hospital/", 0],

];

const medCentersUC_ar = [
    [74, 15, "White Marsh Medical Center", "4920 Campbell Blvd", "Nottingham", "MD", "21236", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/white-marsh-medical-center-100395", 0],
    [62, 19, "Woodlawn Medical Center", "7141 Security Blvd", "Baltimore", "MD", "21244", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/woodlawn-medical-center-limited-services-100393", 0],
    [43, 42, "Kaiser Permanente Baltimore Harbor Medical Center", "815 E Pratt Street", "Baltimore", "MD", "21202", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/kaiser-permanente-baltimore-harbor-medical-center-limited-services-321081", 0],    
    [49, 34, "Kensington Medical Center", "10810 Connecticut Ave", "Kensington", "MD", "20895", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/Kensington-Medical-Center-Limited-Services-100412", 0],
    [40.5, 37, "Unknown", "Unknown", "Unknown", "MD", "00000", "http://google.com", 0],
    [33, 50, "Manassas Medical Center", "10701 Rosemary Dr", "Manassas", "VA", "20109", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/manassas-medical-center-limited-services-100409", 0],
    [60, 50, "Camp Springs Medical Center", "6104 Old Branch Ave", "Temple Hills", "MD", "20748", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/camp-springs-medical-center-limited-services-100424", 0],

];

const medCentersAUC_ar = [
    [66, 25, "South Baltimore County Advanced Urgent Care", "1701 Twin Springs Road", "Halethorpe", "MD", "21227", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/south-baltimore-county-medical-center-301481", 0],
    [44, 27, "Gaithersburg Advanced Urgent Care", "655 Watkins Mill Road", "Gaithersburg", "MD", "20879", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/Gaithersburg-Medical-Center-300451", 0],
    [43, 42, "Tysons Corner Advanced Urgent Care", "8008 Westpark Drive", "McLean", "VA", "22102", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/tysons-corner-medical-center-300450", 0],
    [54, 43, "Kaiser Permanente Capitol Hill Advanced Urgent Care", "700 2nd St. N.E.", "Washington", "DC", "20002", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/kaiser-permanente-capitol-hill-medical-center-300295", 0],
    [62, 42.5, "Largo Advanced Urgent Care", "1221 Mercantile Lane", "Largo", "MD", "20774", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/largo-medical-center-100411", 0],
    [40, 56, "Woodbridge Advanced Urgent Care", "14139 Potomac Mills Road", "Woodbridge", "VA", "22192", "https://healthy.kaiserpermanente.org/maryland-virginia-washington-dc/facilities/woodbridge-medical-center-100394", 0],
    
];


generateMap = function () {
    $ = window.jQuery;
    
    console.log("KP generateMap");
    const parentDiv = $("#kp-map-js").parent();
    const mapContainer = $("<div class='kp-map' />");
    const col_1 = $("<div class='col left' />");
    const col_2 = $("<div class='col right'/>");
    const med_centers_container = $("<div class='mc-container'/>");
    const med_centers = $("<div class='mc'/>");
    const med_centersUC = $("<div class='mc'/>");
    const med_centersAUC = $("<div class='mc'/>");


    col_1.append(`<p class="kp-title">Other Kaiser Permanente Facilities</p>`);

    med_centers.append(`<img src="${medCenterImg}" />`);
    med_centers.append(`<p class="title">Medical Centers</p>`);
    med_centers.append(`<p>Get fast, easy access, often on the same day or next, to your personal doctor and a vast array of specialists, plus lab, imaging and pharmacy—all under one roof.</p>`);
    med_centers.append(`<button id="kp-map-btn-mc" class="kp-map-btn">Show/hide all medical centers</button>`);

    med_centersUC.append(`<img src="${medCenterUCImg}" />`);
    med_centersUC.append(`<p class="title">Medical centers with Urgent Care</p>`);
    med_centersUC.append(`<p>We offer 14 Urgent Care centers throughout the Mid-Atlantic States, five open 24/7. See a doctor and get prescriptions all in one place.</p>`);
    med_centersUC.append(`<button id="kp-map-btn-ucmc" class="kp-map-btn">Show/hide all centers with Urgent Care</button>`);

    med_centersAUC.append(`<img src="${medCenterAUCImg}" />`);
    med_centersAUC.append(`<p class="title">Medical centers with 24/7 Advanced Urgent Care</p>`);
    med_centersAUC.append(`<p>We offer 6 Advanced Urgent Care centers all open 24/7.</p>`);
    med_centersAUC.append(`<button id="kp-map-btn-aucmc" class="kp-map-btn">Show/hide only centers with 24/7 Advanced Urgent Care</button>`);

    col_2.append(`<img class="map-bg" src="${mapImg}" />`)
    //build map
    //hospitals (default)
    hospitals_ar.forEach(function (entry) {
        let item = $(`<div class='map-icon hospital' style='left:${entry[0]}%; top: ${entry[1]}%' data-name="${entry[2]}" data-street="${entry[3]}" data-city="${entry[4]}" data-state="${entry[5]}" data-zip="${entry[6]}"  data-link="${entry[7]}" data-comingsoon="${entry[8]}"></div>`);
        if (entry[8] == 1) {
            item.addClass("kp-map-tool-tip-coming-soon");
        }
        col_2.append(item);
    });

    //medcenters (urgent care)
    medCentersUC_ar.forEach(function (entry) {
        let item = $(`<div class='map-icon medcenter-uc' style='left:${entry[0]}%; top: ${entry[1]}%; display:none' data-name="${entry[2]}" data-street="${entry[3]}" data-city="${entry[4]}" data-state="${entry[5]}" data-zip="${entry[6]}"  data-link="${entry[7]}" data-comingsoon="${entry[8]}"></div>`);

        if (entry[8] == 1) {
            item.addClass("kp-map-tool-tip-coming-soon");
        }
        col_2.append(item);
    });

    //medcenters (advanced urgent care)
    medCentersAUC_ar.forEach(function (entry) {
        let item = $(`<div class='map-icon medcenter-auc' style='left:${entry[0]}%; top: ${entry[1]}%; display:none' data-name="${entry[2]}" data-street="${entry[3]}" data-city="${entry[4]}" data-state="${entry[5]}" data-zip="${entry[6]}"  data-link="${entry[7]}" data-comingsoon="${entry[8]}"></div>`);

        if (entry[8] == 1) {
            item.addClass("kp-map-tool-tip-coming-soon");
        }
        col_2.append(item);
    });   
    
    med_centers_container.append(med_centers);
    med_centers_container.append(med_centersUC);
    med_centers_container.append(med_centersAUC);
    col_1.append(med_centers_container);
    mapContainer.append(col_1);
    mapContainer.append(col_2);
    parentDiv.append(css);
    parentDiv.append(mapContainer);

    //assign events
    $(".kp-map").on("click", ".map-icon", function (e) {
        $(".kp-map-tool-tip").remove();
        let toolTip = $("<div class='kp-map-tool-tip'/>");
        let title = $(this).attr("data-name");
        let link = $(this).attr("data-link");
        let street = $(this).attr("data-street");
        let cityStateZip = $(this).attr("data-city") + ", " + $(this).attr("data-state") + "  " + $(this).attr("data-zip");
        toolTip.append(`<a class="title" href="${link}" target="_blank">${title}</a>`);
        if ($(this).attr("data-comingsoon") == 1) {
            toolTip.addClass("kp-map-tool-tip-coming-soon");
            toolTip.append(`<p class="opening-soon">OPENING 2022</p>`);
        } else {
            toolTip.append(`<p class="street">${street}</p>`);
            toolTip.append(`<p class="city-state-zip">${cityStateZip}</p>`);
        }        
        toolTip.append(`<p class="kp-map-btn-close">X</p>`);
       // $(".kp-map .right").append(toolTip);
        $('body').append(toolTip);
        //let rect = e.target.getBoundingClientRect();        
        //let x = e.clientX - rect.left; //x position within the element.
        //let y = e.clientY - rect.top;  //y position within the element.
        let tt_width = toolTip.outerWidth() / 2;
        let tt_height = toolTip.outerHeight();
        //alert($(this).offset().left);
        toolTip.css({ "left": ($(this).offset().left - tt_width + 20) + "px", "top": ($(this).offset().top - ( tt_height+ 10)) +"px"});
        
    });

    $(document).on("click", ".kp-map-btn-close", function (e) {
        $(".kp-map-tool-tip").remove();
    });

    //btns - urgent care and advanced urgent care
    $(document).on("click", "#kp-map-btn-ucmc", function (e) {

        //clear all tooltips
        $(".kp-map-tool-tip").remove();        

        if ($(this).attr("data-toggle") === "1") { //btn is on             

            showDefaultIconsOnly();
            

        } else { //btn is off - turn it on

            //hide all other selections
            $(".kp-map-btn").attr("data-toggle", 0);
            $(".kp-map-btn").removeClass("kp-map-btn-active");

            //hightlight this button
            $(this).attr("data-toggle", 1);
            $(this).addClass("kp-map-btn-active");

            //hide defaults
            $(".hospital").hide(500);

            //show uc and auc
            $(".medcenter-uc").show(500);
            $(".medcenter-auc").show(500);
        }
    });
    //btn - aucmc - advanced urgent care only
    $(document).on("click", "#kp-map-btn-aucmc", function (e) {

        //clear all tooltips
        $(".kp-map-tool-tip").remove();

        if ($(this).attr("data-toggle") === "1") { //btn is on             

            showDefaultIconsOnly();

        } else { //btn is off - turn it on

            //hide all other selections
            $(".kp-map-btn").attr("data-toggle", 0);
            $(".kp-map-btn").removeClass("kp-map-btn-active");

            //hightlight this button
            $(this).attr("data-toggle", 1);
            $(this).addClass("kp-map-btn-active");

            //hide defaults
            $(".hospital").hide();
            $(".medcenter-uc").hide();

            //show uc and auc
            $(".medcenter-auc").show(500);
        }
    });
    
}

function showDefaultIconsOnly() {
    //unhighlight all buttons
    $(".kp-map-btn").attr("data-toggle", 0);
    $(".kp-map-btn").removeClass("kp-map-btn-active");

    //remove all icons
    $(".medcenter-uc").hide();
    $(".medcenter-auc").hide();

    //show defaults
    $(".hospital").show(500);
}



if (window.jQuery) {
    jQueryCode();
} else {
    //load jquery if not exist    
    const script = document.createElement('script');
    document.head.appendChild(script);
    script.type = 'text/javascript';
    script.src = "//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js";
    script.onload = generateMap;
}

const css = `
<style>


@font-face {
    font-family: 'HCo Gotham';
    src: url(${ font1 }) format('woff2'), url(${ font2 }) format('woff');
    font-style: normal;
    font-weight: 300;
}

@font-face {
    font-family: 'HCo Gotham';
    src: url(${ font3}) format('woff2'), url(${font4 }) format('woff');
    font-style:normal;
    font-weight:undefined;
}

@font-face {
    font-family: 'HCo Gotham';
    src: url(${ font5}) format('woff2'), url(${font6 }) format('woff');
    font-style:normal;
    font-weight:500;
}

@font-face {
    font-family: 'HCo Gotham';
    src: url(${ font7}) format('woff2'), url(${font8 }) format('woff');
    font-style:normal;
    font-weight:700;
}

.kp-map {
    display: flex;
    flex-wrap: wrap;
    font-family:'HCo Gotham', Arial, sans-serif;
    color: #0d1d3fl;
    margin:0px auto;
    flex-direction: column-reverse;
    align-items: stretch;
    position:relative;
}

.kp-map .col{
    margin: 0px auto;
}

.kp-map-tool-tip{
    font-family:'HCo Gotham', Arial, sans-serif;
    max-width: 260px;
    background-color: #FFF;
    border-radius: 10px;
    position:absolute;
    z-index: 5;
    padding: 20px 60px 20px 20px;
    box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
}

.kp-map-tool-tip-coming-soon{
    background-color: #fec943 !important;
}

.kp-map-tool-tip p, .kp-map-tool-tip a{
    line-height :1em;
    padding: 0;
    margin: 0 0 6px 0;
}

.kp-map-tool-tip .title{
    color: #006ba6;
    text-decoration:none;
    font-weight: 700;
    display: block;
}

.kp-map-tool-tip .kp-map-btn-close{
    position: absolute;
    top:10px;
    right:10px;
    cursor: pointer;
    color: #ad2525;
    font-size:1em;
    font-weight: 500;
}

.kp-map button {
    color: #FFF;
    padding: 10px 40px;
    background-color: #006da8;
    text-align: center;
    border: none;
    cursor: pointer;
    font-size: inherit;
    width: 100%;
    margin-bottom: auto;
}

.kp-map button:hover, .kp-map button.kp-map-btn-active {
    background-color: #003a70;
}

.kp-map .left {
    width: calc(100% - 60px);
    padding: 30px;
    text-align:center;
    min-width: 300px;
}

.kp-map .left .mc-container {
    width: 100%;
    text-align:center;
    display:flex;
    flex-direction: row;
}

.kp-map .left .mc-container .mc {
    margin-bottom: 40px;
    width: 100%;
    padding: 0 20px;
    position: relative;
    margin: 0 10px 40px 10px;
}

.kp-map .left p {
    line-height: 1.3em;
    color: #0d1d3f;
}

.kp-map .left .title {
    font-size: 1.3em;
    line-height: 1em;
    font-weight: 500;
    color: #003a70;
}
.kp-map .left img { max-width: 90px; height: auto; }

.kp-map .right {
    width: 100%;
    max-width: 900px;
    position:relative;
}

.kp-map .right .map-bg {
    width: 100%;
    height:auto;
    position: relative;
    z-index: 1;
}

.kp-map-tool-tip .opening-soon{
    color: #ad2525;
}

.kp-map .right .hospital {
    background-image: url(${hospitalImage});
}

.kp-map .right .medcenter {
    background-image: url(${medCenterImg});
}

.kp-map .right .medcenter-uc {
    background-image: url(${medCenterUCImg});
}

.kp-map .right .medcenter-auc {
    background-image: url(${medCenterAUCImg});
}



.kp-map .right .map-icon{
    max-width: 40px;
    position: absolute;
    bottom: auto;
    right: auto;
    border-radius: 50%;
    background-position-x: 50%;    
    background-position-y: 50%;    
    background-repeat: no-repeat;
    background-size: 30px;    
    background-color: #fff;
    min-height: 40px;
    min-width: 40px;
    box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5)  ;
    z-index: 2;
    transform: translate3d(-50%, -50%, 0);
    cursor:pointer;
}

.kp-map .kp-title { font-size: 1.5em;color:#003a70; margin-bottom: 40px;}

@media screen and (min-width: 1200px) {

    .kp-map {
        flex-direction: row;
    }

    .kp-map .left {
        flex-direction: column;
        width: 25%;
        padding: 0;
    }

    .kp-map .left .mc-container {
        flex-direction: column;
        padding: 0;
    }

    .kp-map .left .mc-container .mc {
        width: calc(100% - 60px);
    }
}

@media screen and (max-width: 800px) {

    .kp-map .left .mc-container {
        flex-direction: column;
        width: 100%;
        padding: 0;
    }

    .kp-map .left .mc-container .mc {
        width: calc(100% - 60px);
    }
    .kp-map .right .map-icon{
        /*
        max-height: 30px;
        min-height: 30px;
        max-width: 30px;
        min-width: 30px;
        */
    }

}

</style>
`;