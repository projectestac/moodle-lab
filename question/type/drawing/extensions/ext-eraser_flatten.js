/*
 * ext-helloworld.js
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

methodDraw.addExtension("eraser", function() {
  var current_d, cur_shape_id;
  var canv = methodDraw.canvas;
  var cur_shape;
  var start_x, start_y;
  var svgroot = canv.getRootElem();
  var lastBBox = {}
  var original_paths = [];
  var erase_path = {};
  var line = d3.line().curve(d3.curveMonotoneX);
  var original_paths_ids = [];



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
            // The action taken when the button is clicked on.
            // For "mode" buttons, any other button will
            // automatically be de-pressed.
            svgCanvas.setMode("eraser");
            original_paths = [];
            erase_path = {};
            line = d3.line();
            original_paths_ids = [];
            var svg = d3.select("#svgcontent");




            flatten(document.getElementById('svgcontent'), true);

            var ds = document.getElementById('svgcontent').querySelectorAll("path");

            for(var i = 0; i < ds.length; i++){
                if(!ds[i].getAttribute("d") || ds[i].getAttribute("d") == '' || ds[i].getAttribute("d") == 'undefined') continue;
                if(ds[i].id == 'erase_line') continue;

                var xsw = d3.select('#'+ds[i].id).node();
                var restoredDataset = [];
                 totalLength = xsw.getTotalLength();
                 var step = totalLength * 2; // make less points?
                 for(var v=0;v<totalLength;v++ ){
                   xxs = xsw.getPointAtLength(v + 10);
                   restoredDataset.push([xxs.x,xxs.y]);
                 }
                 original_paths.push(restoredDataset.slice());

              }
          //    this.update(original_paths);




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
          if(!document.getElementById('erase')){
            var svg = d3.select("#svgcontent");
            g_erase = svg.append('g').attr('id', 'erase');
          }

          var e = opts.event;
          var x = start_x = opts.start_x;
          var y = start_y = opts.start_y;
          var ar = [parseFloat(x),parseFloat(y)];
          var svg = d3.select("#svgcontent");

          erase_path.data = [ar].slice();
          var erase_g_tag = document.getElementById('erase_line');
      //   if (!erase_g_tag || erase_g_tag == 'undefined') {
            erase_path.el = g_erase.append('path')
                             .style('fill', 'none')
                             .attr('id', 'erase_line')
                             .style('stroke', '#444')
                             .style('opacity', 0.3)
                             .style('stroke-width', 20*2)
                             .style('stroke-linecap', 'round')
                             .style('stroke-linejoin', 'round');
      //   }

          erase_path.el.datum(erase_path.data).attr('d', function(d) { return line(d) + 'Z'});

          // The returned object must include "started" with
          // a value of true in order for mouseUp to be triggered
          return {started: true};
        }
      },
      // This is triggered when the main mouse button is moved
      // on the editor canvas (not the tool panels)
      mouseMove: function(opts) {console.error("mouse move...");
        // Check the mode on mousedown
        if(svgCanvas.getMode() == "eraser") {
          var e = opts.event;
          var zoom = canv.getZoom();
          var evt = opts.event;
          var x = opts.mouse_x/zoom;
          var y = opts.mouse_y/zoom;
          var ar = [parseFloat(x),parseFloat(y)];
          var sx = ar.slice();
          erase_path.data.push(sx);
          erase_path.el.attr('d', line);
        //  erase_path.el.attr('d', line);
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
          console.error('eraaae', erase_path.data);
          console.error('before', original_paths);
            original_paths = erase(original_paths, erase_path.data);
            console.error('after', original_paths);
        	  this.update(original_paths);
          //  d3.select("#erase_line").remove();
        }
      },
      update: function(opts) {
        // Check the mode on mouseup

        if(svgCanvas.getMode() == "eraser") {
          var svg = d3.select("#svgcontent");
          var gpaths = svg.select('#paths');
          var p = gpaths.selectAll('path').data(opts);
          console.error("update", opts);
        //  d3.select("#erase").remove();
          p.enter().append('path').attr('id', 'path_after_erase');
          console.error("linel:", line);
          p.attr('d', line);
          p.exit().remove();
         d3.select("#erase_line").remove();
        }

      }

    };


});
