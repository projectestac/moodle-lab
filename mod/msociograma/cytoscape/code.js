$(function(){ // on dom ready

$('#cy').cytoscape({
  layout: {
    name: 'cose',
    padding: 10,
	//preset: true
    randomize: true
  },
  
  style: cytoscape.stylesheet()
    .selector('node')
      .css({
        'shape': 'data(faveShape)',
        'width': 'mapData(weight, 40, 80, 40, 100)',
        'content': 'data(name)',
        'text-valign': 'center',
        'text-outline-width': 2,
        'text-outline-color': 'data(faveColor)',
        'background-color': 'data(faveColor)',
        'color': '#fff'
      })
    .selector(':selected')
      .css({
        'border-width': 3,
        'border-color': '#333'
      })
    .selector('edge')
      .css({
        'curve-style': 'bezier',
        'opacity': 0.666,
        'width': 'mapData(strength, 70, 100, 2, 6)',
        'target-arrow-shape': 'triangle',
        'source-arrow-shape': 'circle',
        'line-color': 'data(faveColor)',
        'source-arrow-color': 'green',
        'target-arrow-color': 'red'
      })
    .selector('edge.questionable')
      .css({
        'line-style': 'dotted',
        'target-arrow-shape': 'diamond'
      })
    .selector('.faded')
      .css({
        'opacity': 0.25,
        'text-opacity': 0
      }),
  
  elements: {
    nodes: [
      { data: { id: 'j', name: 'Jerry', weight: 65, faveColor: 'green', faveShape: 'octagon' } },
      { data: { id: 'e', name: 'Elaine', weight: 65, faveColor: 'green', faveShape: 'octagon' } },
      { data: { id: 'k', name: 'Kramer', weight: 65, faveColor: 'green', faveShape: 'octagon' } },
	   { data: { id: 'm', name: 'marco Alarcón', weight: 65, faveColor: 'green', faveShape: 'octagon' } },
      { data: { id: 'g', name: 'George', weight: 65, faveColor: 'green', faveShape: 'octagon' } }
    ],
    edges: [
      { data: { source: 'm', target: 'e', faveColor: '#6FB1FC', strength: 80 } },
      { data: { source: 'm', target: 'k', faveColor: '#6FB1FC', strength: 80 } },
      { data: { source: 'm', target: 'g', faveColor: '#6FB1FC', strength: 80 } },
     
      { data: { source: 'e', target: 'm', faveColor: '#6FB1FC', strength: 80 } },
      //{ data: { source: 'e', target: 'k', faveColor: '#6FB1FC', strength: 60 }, classes: 'questionable' },
      
      { data: { source: 'k', target: 'm', faveColor: '#6FB1FC', strength: 80 } },
      { data: { source: 'k', target: 'e', faveColor: '#6FB1FC', strength: 80 } },
      { data: { source: 'k', target: 'g', faveColor: '#6FB1FC', strength: 80 } },
      
      { data: { source: 'g', target: 'm', faveColor: '#6FB1FC', strength: 80,top:0, left:0 } },
	   { data: { source: 'm', target: 'j', faveColor: '#6FB1FC', strength: 80 } }
    ]
  },
  
  ready: function(){
    window.cy = this;
    
    // giddy up
  }
  
});

cy.on('tap', 'node', function(e){
  alert('ok');
  var x = cy.$('#j').position('x');

// get the whole position for e
var pos = cy.$('#e').position();

// set y for j
cy.$('#j').position('y', 100);

// set multiple
cy.$('#e').position({
  x: 123,
  y: 200
});

});

}); // on dom ready