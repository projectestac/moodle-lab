/**
 * This is JavaScript code that handles hand drawing on mouse events and painting pre-existing drawings.
 * @package    qtype
 * @subpackage drawing
 * @copyright  ETHZ LET <amr.hourani@id.ethz.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

YUI.add('moodle-qtype_drawing-form', function(Y) {
    var CSS = {
    },
    SELECTORS = {
        drawingLCPICKER: 'div[class="lc-picker"]',
        drawingLCOPTIONS: 'div[class="literally lc-picker"]',
        DRAWINGCANVAS: 'div[class="literally"]',
        DRAWINGCANVASID:'#qtype_drawing_div_id',
        GENERICCANVAS: 'canvas[class="qtype_drawing_canvas"]',
        CANVASWIDTH: '#qtype_drawing_backgroundwidth',
        CANVASHEIGHT: '#qtype_drawing_backgroundheight',
        ALLCANVASES: 'canvas',
        READONLYCANVAS: 'canvas[class="qtype_drawing_canvas readonly-canvas"]',
        FILEPICKER: '#id_qtype_drawing_image_file',
        SELECTEDFILEPICKER: '#id_qtype_drawing_drawing_background_image_selected',
        FILEPICKERFIELDSET: 'fieldset[id$=qtype_drawing_drawing_background_image]',
        FILEPICKERFIELDSETANOTHER: 'fieldset[id$=qtype_drawing_drawing_background_image_selected]',
        PRESERVERATIO: '#id_preservear',
        BACKGROUNDUPLOADED: 'input[name="backgrounduploaded"]',
        DRAWINGRADIUS: '#id_radius',
        ERASERADIUS: '#erase_radius',
        DRAWINGCOLOR: '#id_color',
        BRUSHVOLUME: '#brushvolume',
        QUICKCOLOR1: '#quickcolor1',
        QUICKCOLOR2: '#quickcolor2',
        QUICKCOLOR3: '#quickcolor3',
        QUICKCOLOR4: '#quickcolor4',
        QUICKRADIUS1: '#quickradius1',
        QUICKRADIUS2: '#quickradius2',
        QUICKRADIUS3: '#quickradius3',
        QUICKRADIUS4: '#quickradius4',
        ZOOMCANVAS: '#zoomcanvas',
        DRAWINGJUSTCHANGED: '#drawingchanged',
        DRAWINGEDITMODEBG: '#pre_existing_background_data',
        CLOSECANVASOVERLAY: '#closecanvasoverlay',
        CHOOSEFILEBUTTON: 'input[name="qtype_drawing_image_filechoose"]',
        CHOOSEANOTHERFILEBUTTON: 'input[name="qtype_drawing_image_filechoose_another"]',
        ERASERBUTTON: 'img[class="qtype_drawing_eraser"]',
        UNDOBUTTON: 'img[class="qtype_drawing_undo"]',
        ERASERTOOLBUTTON: 'img[class="qtype_drawing_eraser_tool"]',
        CURRENTTOOL: 'img[class="qtype_drawing_current_tool"]',
        CONTAINERDIV: 'div[class="qtype_drawing_container_div"]',
        NOBACKGROUNDIMAGESELECTEDYET: 'div[class="qtype_drawing_no_background_image_selected_yet"]',
        CANVASTEXTAREAEDITMODE: 'textarea[name="qtype_drawing_textarea_id_0"]',
        CANVASTEXTAREATESTMODE: 'textarea[id="qtype_drawing_textarea_id_',
        QUESTION_AREA_DIV: 'div[class="qtype_drawing_id_',
    };
    Y.namespace('Moodle.qtype_drawing.form');

    Y.Moodle.qtype_drawing.form = {

        canvasContext: new Array(),
        drawingRadius: new Array(),
        drawingColor: new Array(),
        lastdrawingColor: new Array(),
        eraseRadius: new Array(),
        eraserToolOn: new Array(),
        RadiusChanged: new Array(),
        emptyCanvasDataURL: new Array(),
        restorePoints: new Array(),
        filepicker_change_sub: null,
        choose_new_image_file_click_sub: null,
        eraser_click_sub: null,
        undo_click_sub: null,
        eraser_tool_click_sub: null,
        canvas_mousedown_sub: null,
        canvas_touchstart_sub: null,
        canvas_touchmove_sub: null,
        canvas_touchend_sub: null,

        canvas_pointerstart_sub: null,
        canvas_pointermove_sub: null,
        canvas_pointerend_sub: null,

        canvas_mouseup_sub: null,
        canvas_mouseout_sub: null,
        drawing_radius_change_sub: null,
        drawing_radius_mouseup_sub: null,
        drawing_color_change_sub: null,
        quickcolor1_click_sub: null,
        quickcolor2_click_sub: null,
        quickcolor3_click_sub: null,
        quickcolor4_click_sub: null,
        quickradius1_click_sub: null,
        quickradius2_click_sub: null,
        quickradius3_click_sub: null,
        quickradius4_click_sub: null,
        zoomcanvas_click_sub: null,
        closecanvasoverlay_click_sub: null,
        edit_mode: false,
        resize_sub: null,
        drawing_question_id: null,
        contextmenu_sub: null,
        resizecanvasw_sub: null,
        resizecanvash_sub: null,
        originalheight: null,
        originalwidth: null,
        naturalheight: null,
        naturalwidth: null,

        qtype_drawing_size_listener: function(originalwidth, originalheight){
            this.originalheight = originalheight;
            this.originalwidth = originalwidth;
            if(!this.resizecanvasw_sub) {
                this.resizecanvasw_sub = Y.delegate('keyup', this.resizecanvasw,  Y.config.doc, SELECTORS.CANVASWIDTH, this);
            }
            if(!this.resizecanvash_sub) {
                this.resizecanvash_sub = Y.delegate('keyup', this.resizecanvash,  Y.config.doc, SELECTORS.CANVASHEIGHT, this);
            }
        },
        resizecanvasw: function(e){
            if (e.which < 48 || e.which > 57){
                 e.preventDefault();
            }
            if(Y.one(SELECTORS.PRESERVERATIO).get('checked')){
                this.calculateheight(Y.one(SELECTORS.CANVASWIDTH).get('value'));
            }
        },
        resizecanvash: function(e){
            if (e.which < 48 || e.which > 57){
                 e.preventDefault();
            }
            if(Y.one(SELECTORS.PRESERVERATIO).get('checked')){
                this.calculatewidth(Y.one(SELECTORS.CANVASHEIGHT).get('value'));
            }
        },
        calculateheight: function(width){

            if(!this.naturalwidth){
                 this.naturalwidth = this.originalwidth;

            }

            if(!this.naturalheight){
                 this.naturalheight = this.originalheight;
            }
            if(!width){
                width = this.originalwidth;
            }
            naturalheight = this.naturalheight;
            naturalwidth = this.naturalwidth;
            aspectratio = naturalheight / naturalwidth;
            var height = Math.round(width * aspectratio);
            Y.one(SELECTORS.CANVASHEIGHT).set('value', height);
        },
        calculatewidth: function(height){
            if(!this.naturalwidth){
                 this.naturalwidth = this.originalwidth;
            }
            if(!this.naturalheight){
                 this.naturalheight = this.originalheight;
            }
            if(!height){
                height = this.originalheight;
            }
            naturalheight = this.naturalheight;
            naturalwidth = this.naturalwidth;
            aspectratio = naturalwidth / naturalheight;
            var width = Math.round(height * aspectratio);
            Y.one(SELECTORS.CANVASWIDTH).set('value', width);
        },
        newquestion: function() {
            this.drawing_question_id = 0;
            this.emptyCanvasDataURL[0] = 1;
            // This is a question edit or "add new" form.
            // Check if this is an edit form with a pre-existing (on the server) saved image.
            if (Y.one(SELECTORS.CHOOSEANOTHERFILEBUTTON) != null) {
                this.emptyCanvasDataURL[questionID] = 0;
                // So if there's a pre-existing background image.
                // Hide the file-picker widget (until further notice... (click by 'choose another background'...).
                this.edit_mode = true;
                Y.one(SELECTORS.FILEPICKERFIELDSET).setStyles({display: 'none'});
                Y.delegate('click', function() { Y.one(SELECTORS.CHOOSEFILEBUTTON).simulate('click'); }, Y.config.doc, SELECTORS.CHOOSEANOTHERFILEBUTTON, this);
            }
            if(!this.filepicker_change_sub) {
                this.filepicker_change_sub = Y.delegate('change', this.filepicker_change, Y.config.doc, SELECTORS.FILEPICKER, this);
            }
            if(!this.choose_new_image_file_click_sub) {
                this.choose_new_image_file_click_sub = Y.delegate('click', this.choose_new_image_file_click, Y.config.doc, SELECTORS.CHOOSEFILEBUTTON, this);
            }
            if (!this.contextmenu_sub) {
                this.contextmenu_sub = Y.delegate('contextmenu', this.DisableConxMenu, Y.config.doc, SELECTORS.DRAWINGCANVAS, this);// DRAWINGCANVASID
            }
        },
        editquestion: function(questionID, currentheight, currentwidth) {
            this.drawing_question_id = questionID;

            if (Y.one(SELECTORS.CHOOSEANOTHERFILEBUTTON) != null) {
                this.emptyCanvasDataURL[questionID] = questionID;
                Y.one(SELECTORS.CHOOSEANOTHERFILEBUTTON).addClass('btn btn-secondary fp-btn-choose');
                // So if there's a pre-existing background image.
                this.naturalheight = currentheight;
                this.naturalwidth = currentwidth;
                this.originalwidth = this.naturalwidth;
                this.originalheight = this.currentheight;
                // Hide the file-picker widget (until further notice... (click by 'choose another background'...).
                this.edit_mode = true;
                Y.one(SELECTORS.FILEPICKERFIELDSET).setStyles({display: 'none'});

                Y.delegate('click', function() { Y.one(SELECTORS.CHOOSEFILEBUTTON).simulate('click'); }, Y.config.doc, SELECTORS.CHOOSEANOTHERFILEBUTTON, this);
            }
            if(!this.filepicker_change_sub) {
                this.filepicker_change_sub = Y.delegate('change',    this.filepicker_change,     Y.config.doc, SELECTORS.FILEPICKER, this);
            }
            if(!this.choose_new_image_file_click_sub) {
                this.choose_new_image_file_click_sub = Y.delegate('click', this.choose_new_image_file_click, Y.config.doc, SELECTORS.CHOOSEFILEBUTTON, this);
            }
            if (!this.contextmenu_sub) {
                this.contextmenu_sub = Y.delegate('contextmenu', this.DisableConxMenu, Y.config.doc, SELECTORS.DRAWINGCANVAS, this);// DRAWINGCANVASID
            }

        },
        attemptquestion: function(questionID, background, width, height, datatype) {

            return;

        },

        init: function(questionID, correctAnswer, canvasInstanceID, originalBackGroundURL) {
            if (typeof correctAnswer != 'undefined' && correctAnswer != 'undefined') {
                // A correct answer is provided by the argument list--so this means the canvas is to be treated as READ ONLY
                this.draw_correct_answer(questionID, correctAnswer, canvasInstanceID, originalBackGroundURL);
            }

        },
        draw_correct_answer: function(questionID, correctAnswer, canvasInstanceID, originalBackGroundURL) {

            this.create_canvas_context(questionID, true,originalBackGroundURL,correctAnswer);

        },
        choose_new_image_file_click: function(e) {
            if (this.edit_mode == true) {
                if (confirm(M.util.get_string('are_you_sure_you_want_to_pick_a_new_bgimage', 'qtype_drawing')) == false) {
                    Y.one('.file-picker.fp-generallayout').one('.yui3-button.yui3-button-close').simulate("click");
                }
            }
        },
        is_canvas_empty: function(questionID) { if (questionID == 0) {return true;}
            if (questionID == 0) {
                canvasNode = Y.one(SELECTORS.GENERICCANVAS);
            } else {
                Y.all(SELECTORS.GENERICCANVAS).each(function(node) {
                    if (node.ancestor().getAttribute('class') == 'qtype_drawing_id_' + questionID) {
                        canvasNode = node;
                    }
                }.bind(this));
            }
            if (this.emptyCanvasDataURL[questionID] != 0 && canvasNode.getDOMNode().toDataURL() != this.emptyCanvasDataURL[questionID]) {
                return false;
            }
            return true;
        },
        filepicker_change: function(e) {
            if (this.edit_mode == true) {
                Y.one(SELECTORS.FILEPICKERFIELDSET).setStyles({display: 'block'});
                Y.one(SELECTORS.FILEPICKERFIELDSETANOTHER).setStyles({display: 'none'});
            }
            var imgURL = Y.one('#id_qtype_drawing_image_file').ancestor().one('div.filepicker-filelist a').get('href');
            var image = new Image();
            image.src = imgURL;
            image.id = '#id_qtype_drawing_uploaded_image';
            image.onload = function () {
                questionID = this.drawing_question_id;

                if(document.getElementById('pre_existing_background_data')){
                     document.getElementById('pre_existing_background_data').value = '';
                }
                Y.one(SELECTORS.BACKGROUNDUPLOADED).set('value', 1);
                if(image.width && image.width != null){
                    this.naturalheight = image.height;
                    this.naturalwidth = image.width;
                    Y.one(SELECTORS.CANVASWIDTH).set('value', image.width);
                    Y.one(SELECTORS.CANVASHEIGHT).set('value', image.height);
                } else {
                    // SVG file can be tricky, reset to default.
                    Y.one(SELECTORS.CANVASWIDTH).set('value', this.originalwidth);
                    Y.one(SELECTORS.CANVASHEIGHT).set('value', this.originalheight);
                }
                Y.one('.filepicker-filename').append("<br /><br />" + '<div style="background-color: #000; background-blend-mode: multiply; display:inline-block"><img src="' + imgURL + '" style="width: 100%; height: auto;"></div>');// .set('src', imgURL);

            }.bind(this);
        },
        create_canvas_new_question: function(questionID) {
            if (questionID == 0) {
                      Xtextarea = Y.one(SELECTORS.CANVASTEXTAREAEDITMODE);
            } else {
                Xtextarea = Y.one(SELECTORS.CANVASTEXTAREATESTMODE + questionID + '"]');
            }
            var image = new Image();
            Xtextarea.set('value', '');
        },
        create_canvas_edit_question: function(questionID) {
            Xtextarea = Y.one(SELECTORS.CANVASTEXTAREATESTMODE + questionID + '"]');
        },
        create_canvas_context: function(questionID, applyTextArea,originalBackGroundURL,correctAnswer) {
            if (typeof applyTextArea == 'undefined') {
                applyTextArea = true;
            }
            if (questionID == 0) {
                      Xtextarea = Y.one(SELECTORS.CANVASTEXTAREAEDITMODE);
            } else {
                Xtextarea = Y.one(SELECTORS.CANVASTEXTAREATESTMODE + questionID + '"]');
            }

            var image = new Image();
            if (Xtextarea != null) {
                if (applyTextArea == false) {
                    Xtextarea.set('value', '');
                }
            } else {
                alert("No Pre-set Drawing");

            }
        },
        canvas_get_textarea: function(node) {
            questionID = this.canvas_get_question_id(node);
            if (questionID == 0) {
                       return Y.one(SELECTORS.CANVASTEXTAREAEDITMODE);
            } else {
                    return Y.one(SELECTORS.CANVASTEXTAREATESTMODE + questionID + '"]');
            }
        },
        canvas_get_question_id: function(node) {

            if (node.ancestor().getAttribute('class').indexOf('qtype_drawing_id') == -1) {
                return 0;
            } else {
                return node.ancestor().getAttribute('class').replace(/qtype_drawing_id_/gi, '');
            }
        },
        canvas_mouseup: function(e) {
        },

        DisableConxMenu:  function(e) {
             e.preventDefault();
             e.stopPropagation();
             return false;
        },
    };
}, '@VERSION@', {requires: ['node', 'event'] });
