$(document).ready(function(){
    $("#myController").jFlow({
        slides: "#slides",  // the div where all your sliding divs are nested in
        controller: ".jFlowControl", // must be class, use . sign
        slideWrapper : "#jFlowSlide", // must be id, use # sign
        selectedWrapper: "jFlowSelected",  // just pure text, no sign
        width: "675px",  // this is the width for the content-slider
        height: "446px",  // this is the height for the content-slider
        duration: 1000,  // time in miliseconds to transition one slide
        prev: ".jFlowPrev", // must be class, use . sign
        next: ".jFlowNext" // must be class, use . sign
    });
});

