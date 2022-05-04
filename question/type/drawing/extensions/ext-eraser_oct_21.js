/*
 * ext-eraser.js
 *
 * Licensed under the Apache License, Version 2
 *
 * Copyright(c) 2010 Alexis Deveria
 *
 */

/*
  This is a very basic SVG-Edit extension. It adds a "Hello World" button in
  the left panel. Clicking on the button, and then the canvas will show the
  user the point on the canvas that was clicked on.
*/

methodDraw.addExtension("eraser", function(S) {
  var current_d, cur_shape_id;
  var canv = methodDraw.canvas;
  var cur_shape;
  var lastzoom = 1;
  var start_x, start_y;
  var svgroot = canv.getRootElem();
  var svgcontent = canv.getContentElem();
  var lastBBox = {}
  var original_paths = [];
  var erase_path = {};
  var line = d3.line().curve(d3.curveNatural);
  var original_paths_ids = [];
  var original_paths_saved = [];
  var last_known_drawing = null;
  var passedover = [];
  var changedlines = [];
  var updatedlinespecs = [];
  // Default configuration options
  var path_specs = {
    stroke: "#000",
    style: "pointer-events:inherit",
    id: "svg_",
    fill: "none",
    strokewidth: "2",
    strokelinecap: "round",
    strokelinejoin: "round",
    transform: null,
    xfilter: null,
    strokedasharray: null,
    opacity: 1,
    fillopacity: 1,
    strokeopacity: 1
  };
  var allpathspecs = [];

  var ChangeElementCommand = svgedit.history.ChangeElementCommand,
   InsertElementCommand = svgedit.history.InsertElementCommand,
   RemoveElementCommand = svgedit.history.RemoveElementCommand,
   BatchCommand = svgedit.history.BatchCommand,
  addToHistory = function(cmd) { canv.undoMgr.addCommandToHistory(cmd); };

    return {
      name: "Eraser",
      // For more notes on how to make an icon file, see the source of
      // the hellorworld-icon.xml
      svgicons: "extensions/eraser-icon.xml",

      // Multiple buttons can be added in this array
      buttons: [{
        // Must match the icon ID in eraser-icon.xml
        id: "tool_eraser",

        // This indicates that the button will be added to the "mode"
        // button panel on the left side
        type: "mode",

        // Tooltip text
        title: "Erase",

        // Events
        events: {
          'click': function() {
            // Save initial state before even flattinging.
            methodDraw.SaveDrawingToMoodle();
            // The action taken when the button is clicked on.
            // For "mode" buttons, any other button will
            // automatically be de-pressed.
            svgCanvas.setMode("eraser");
            original_paths = [];
            erase_path = {};
            line = d3.line();
            original_paths_ids = [];

            $('#strokestyle_div').hide();
            canv.setZoom(1);
            $('#zoom_select').val(100).change();
            $('#zoom_panel').hide();

            xpaths = [];
            svgElement = [];
            xflatten(document.getElementById('svgcontent'));//, true, true, true
            var svgElement = document.getElementById('svgcontent');
            var xpaths = flattenSVG(svgElement);

            allpathspecs = [];
            var m = xpaths;
          //  var res = [];
            var svg = d3.select("#svgcontent");

            for(var iz = 0; iz < m.length; iz++){
                var pointsarr = m[iz].points;
                var path_id = m[iz].id;
              //  res.push(pointsarr.slice());
                original_paths.push(pointsarr.slice());
                 path_specs = {
                  stroke: m[iz].stroke,
                  style: m[iz].style,
                  id: m[iz].id,
                  fill: m[iz].fill,
                  strokewidth: m[iz].strokewidth,
                  strokelinecap: m[iz].strokelinecap,
                  strokelinejoin: m[iz].strokelinejoin,
                  transform: m[iz].transform,
                  xfilter: m[iz].xfilter,
                  strokedasharray: m[iz].strokedasharray,
                  opacity: m[iz].opacity,
                  fillopacity: m[iz].fillopacity,
                  strokeopacity: m[iz].strokeopacity,
                };

                allpathspecs.push(path_specs);


            }

            original_paths_saved = original_paths;


            var svg = d3.select("#svgcontent");
            var gpaths = svg.select('#paths');



            gpaths.selectAll("path").remove();

            var svg = d3.select("#svgcontent");
            var gpaths = svg.select('#paths');
            var p = gpaths.selectAll('path').data(original_paths);
            p.enter()
           .append('path')
           .attr('fill', 'none')
           .attr('stroke', '#000')
           .attr('stroke-width', 3.5)
           .attr('stroke-linecap', 'round')
           .attr('stroke-linejoin', 'round');



          var p = gpaths.selectAll('path').data(original_paths);
          p.attr('d', line);

          p.exit().remove();


          var xsvg = d3.select("#svgcontent");
          var xgpaths = xsvg.select('#paths');
          var xp = xgpaths.selectAll('path');
          xp.each(function(d,i) {
              d3.select(this).attr('id', allpathspecs[i].id);
              d3.select(this).attr('stroke', allpathspecs[i].stroke);
              d3.select(this).attr('stroke-width', allpathspecs[i].strokewidth);
              d3.select(this).attr('fill', allpathspecs[i].fill);
              d3.select(this).attr('style', allpathspecs[i].style);
              d3.select(this).attr('stroke-linecap', allpathspecs[i].strokelinecap);
              d3.select(this).attr('stroke-linejoin', allpathspecs[i].strokelinejoin);
              d3.select(this).attr('transform', allpathspecs[i].transform);
              d3.select(this).attr('filter', allpathspecs[i].xfilter);
              d3.select(this).attr('stroke-dasharray', allpathspecs[i].strokedasharray);
              d3.select(this).attr('opacity', allpathspecs[i].opacity);
              d3.select(this).attr('fill-opacity', allpathspecs[i].fillopacity);
              d3.select(this).attr('stroke-opacity', allpathspecs[i].strokeopacity);


          });

          // Save state after flattinging.
           methodDraw.SaveDrawingToMoodle();

          }
        }
      }],
      callback: function() {
      },
      // This is triggered when the main mouse button is pressed down
      // on the editor canvas (not the tool panels)
      mouseDown: function(opts) {
        // Check the mode on mousedown
        if(svgCanvas.getMode() == "eraser") {
            $("#strokestyle_div").hide();
            $("#fastcolorpicks").hide();

          /*
        var qid = $('#fhd_question_id').val();
        var lastsavedanswerelem = window.parent.$("#qtype_drawing_textarea_id_"+qid).val();
        */
            var zoom = canv.getZoom();
            lastzoom = zoom;

          methodDraw.loadFromString(svgCanvas.getSvgString());

        // Save initial state before even flattinging.
        // methodDraw.SaveDrawingToMoodle();

          if(!document.getElementById('erase')){
            var svg = d3.select("#svgcontent");
            g_erase = svg.append('g').attr('id', 'erase');
          } else {
            var svg = d3.select("#svgcontent");
            var drawingpaths = svg.select("#paths");
            g_erase = drawingpaths.select('erase');
          }



//////////////////////

  allpathspecs = [];
  var xsvg = d3.select("#svgcontent");
  var xgpaths = xsvg.select('#paths');
  var xp = xgpaths.selectAll('path');
  xp.each(function(d,i) {

if(d3.select(this).attr("id") != 'erase_line'){

       path_specs = {
        stroke: d3.select(this).attr("stroke"),
        style: d3.select(this).attr("style"),
        id: d3.select(this).attr("id"),
        fill: d3.select(this).attr("fill"),
        strokewidth: d3.select(this).attr("stroke-width"),
        strokelinecap: d3.select(this).attr("stroke-linecap"),
        strokelinejoin: d3.select(this).attr("stroke-linejoin"),
        transform: d3.select(this).attr("transform"),
        xfilter: d3.select(this).attr("filter"),
        strokedasharray: d3.select(this).attr("stroke-dasharray"),
        opacity: d3.select(this).attr("opacity"),
        fillopacity: d3.select(this).attr("fill-opacity"),
        strokeopacity: d3.select(this).attr("stroke-opacity")

      };

      allpathspecs.push(path_specs);

  }


});

////////////////////



          var e = opts.event;
          var x = start_x = opts.start_x/zoom;
          var y = start_y = opts.start_y/zoom;
          var ar = [parseFloat(x),parseFloat(y)];
          var svg = d3.select("#svgcontent");

          erase_path.data = [ar].slice();

            erase_path.el =svg.select("#erase").append('path')
                             .style('fill', 'none')
                             .attr('id', 'erase_line')
                             .style('stroke', '#444')
                             .style('opacity', 0.3)
                             .style('stroke-width', canv.getStrokeWidth() * 2)
                             .style('stroke-linecap', 'round')
                             .style('stroke-linejoin', 'round');

          erase_path.el.datum(erase_path.data).attr('d', function(d) { return line(d) + 'Z'});
          // The returned object must include "started" with
          // a value of true in order for mouseUp to be triggered

          return {started: true};
        }
      },
      // This is triggered when the main mouse button is moved
      // on the editor canvas (not the tool panels)
      mouseMove: function(opts) {
        // Check the mode on mousedown

        if(svgCanvas.getMode() == "eraser") {

            $("#strokestyle_div").hide();
            $("#fastcolorpicks").hide();
          var e = opts.event;
          var zoom = canv.getZoom();
          var evt = opts.event;
          var x = opts.mouse_x/zoom;
          var y = opts.mouse_y/zoom;
          var ar = [parseFloat(x),parseFloat(y)];
          var sx = ar.slice();
          erase_path.data.push(sx);
          erase_path.el.attr('d', line);

          // The returned object must include "started" with
          // a value of true in order for mouseUp to be triggered
          return {started: true};
        }
      },
      // This is triggered from anywhere, but "started" must have been set
      // to true (see above). Note that "opts" is an object with event info
      mouseUp: function(opts) {
        // Check the mode on mouseup
        if(svgCanvas.getMode() == "eraser") {
            $("#strokestyle_div").hide();
            $("#fastcolorpicks").hide();
            Xoriginal_paths = erase(original_paths, erase_path.data, canv.getStrokeWidth(), allpathspecs);
            original_paths = Xoriginal_paths[0];
            updatedlinespecs = Xoriginal_paths[1];


              this.update(original_paths);
        }
      },
      update: function(opts) {
        // Check the mode on mouseup

        if(svgCanvas.getMode() == "eraser") {

          var svg = d3.select("#svgcontent");
          var gpaths = svg.select('#paths');


          var p = gpaths.selectAll('path').data(opts);






  p.enter().append('path').attr("fill", "none")
                          .attr("stroke", '#000') // #ff3333
                          .attr("stroke-width", '3.5') // 20
                          .attr("fill", 'none') // none
                          .attr("shape-rendering", "geometricPrecision")
                          .attr("data-justcreated", "1");



var p = gpaths.selectAll('path').data(opts);


        p.attr('d', line);
        p.exit().remove();
         d3.select("#erase_line").remove();






         var xsvg = d3.select("#svgcontent");
         var xgpaths = xsvg.select('#paths');
         var xp = xgpaths.selectAll('path');
         var concatpaths = '';
         var dontuseanymore = [];
         xp.each(function(d,i) {



             if(dontuseanymore.includes(updatedlinespecs[i].id)){
                 d3.select(this).attr('id', updatedlinespecs[i].id +'_'+Math.random()); //+ +'_'+Math.random()
             } else {
                 d3.select(this).attr('id', updatedlinespecs[i].id); //+
             }

             dontuseanymore.push(updatedlinespecs[i].id);

             d3.select(this).attr('stroke', updatedlinespecs[i].stroke);
             d3.select(this).attr('stroke-width', updatedlinespecs[i].strokewidth);
             d3.select(this).attr('fill', updatedlinespecs[i].fill);
             d3.select(this).attr('style', updatedlinespecs[i].style);
             d3.select(this).attr('stroke-linecap', updatedlinespecs[i].strokelinecap);
             d3.select(this).attr('stroke-linejoin', updatedlinespecs[i].strokelinejoin);
             d3.select(this).attr('transform', updatedlinespecs[i].transform);
             d3.select(this).attr('filter', updatedlinespecs[i].xfilter);
             d3.select(this).attr('stroke-dasharray', updatedlinespecs[i].strokedasharray);
             d3.select(this).attr('opacity', updatedlinespecs[i].opacity);
             d3.select(this).attr('stroke-opacity', updatedlinespecs[i].strokeopacity);
             d3.select(this).attr('fill-opacity', updatedlinespecs[i].fillopacity);



         });




          methodDraw.SaveDrawingToMoodle();



        }


}

    };


});
