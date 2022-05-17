(function() {

 // modern Chrome requires { passive: false } when adding event
var supportsPassive = false;
try {
  window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
    get: function () { supportsPassive = true; }
  }));
} catch(e) {}

var wheelOpt = supportsPassive ? { passive: false } : false;
document.addEventListener("touchstart", touchHandler, true);
document.addEventListener("touchmove", touchHandler, wheelOpt);
document.addEventListener("touchend", touchHandler, true);
document.addEventListener("touchcancel", touchHandler, true);
document.addEventListener("doubletap", touchHandler, true);

function touchHandler(evt) {

  if(evt.type == "touchend" && svgCanvas.getMode() == 'text'){
    return;
  }

  if (evt.touches.length > 1 || (evt.type == "touchend" && evt.touches.length > 0)){
    return;
  }

  if(evt.type == "touchmove"){
        evt.preventDefault();
  }
  var newEvt = document.createEvent("MouseEvents");
  var type = null;
  var touch = null;

  switch (evt.type) {
    case "touchstart":
      type = "mousedown";
      touch = evt.changedTouches[0];
      break;
    case "touchmove":
      type = "mousemove";
      touch = evt.changedTouches[0];
      break;
    case "touchend":
      type = "mouseup";
      touch = evt.changedTouches[0];
      break;
  }

  newEvt.initMouseEvent(type, true, true, window, 0,
    touch.screenX, touch.screenY, touch.clientX, touch.clientY,
    evt.ctrlKey, evt.altKey, evt.shiftKey, evt.metaKey, 0, null);
  touch.target.dispatchEvent(newEvt);

}

})();
