$(document).ready(function() {
    if($('.container-avance').length){
        Avance.init();
    }
    $('.carousel').carousel({
         interval: 5000
    });
    //Traba absurda
    var /*traba_absurda = $('#traba_absurda'),
        mas_info_traba_absurda = $('.mas_info_traba_absurda').hide(),*/
        check_otro_servicio_relacionado = $('#servicio_relacionado_11'),
        otro_servicio_relacionado = $('#otro_servicio_relacionado'),
        input_edad = $('#edad');
    /*if(traba_absurda.length){
        traba_absurda.on('change', function(e){
            if(traba_absurda.attr('checked')){
                mas_info_traba_absurda.show();
            }else{
                mas_info_traba_absurda.hide();
            }
            e.preventDefault();
        });
    }*/
    if($('#rut_empresa').length){
        $('#rut_empresa').Rut({
                format:true
        });
    }
    if(check_otro_servicio_relacionado.length){
        if(otro_servicio_relacionado.val() === ''){
            otro_servicio_relacionado.css('display','none');
        }else{
            otro_servicio_relacionado.css('display','block');
        }
        check_otro_servicio_relacionado.on('change', function(e){
            if(check_otro_servicio_relacionado.attr('checked')){
                otro_servicio_relacionado.css('display','block');
            }else{
                otro_servicio_relacionado.css('display','none');
            }
            e.preventDefault();
        });
    }
    if(input_edad.length){
        input_edad.on('keydown', function(e){
            return numericVal(e.keyCode);
        });
        input_edad.on('blur', function(e){
            var elem = $(this);
            if(!$.isNumeric(elem.val())){
                elem.val(0);
            }
        });
    }
    var contTramites = $('#contenedor-tramites'),
        tramites = contTramites.find('.bloque-tramite');
    if(contTramites.length){
        $('body').append('<div class="tramites-overlay"></div>');
        var tramitesOverlay = $('.tramites-overlay');
        contTramites.isotope({
            itemSelector : '.bloque-tramite',
            containerStyle : {
                position:'relative'
            },
            getSortData : {
                institucion : function( elem ){
                    return elem.find('.filtro-institucion').text();
                },
                tramite : function( elem ){
                    return elem.find('.filtro-tramite').text();
                },
                cantidad : function( elem ){
                    return parseInt(elem.find('.filtro-cantidad').text(), 10);
                },
                tipo_tramite : function( elem ){
                    return elem.find('.filtro-tipo_tramite').text();
                }
            },
            masonry: {
                columnWidth:(contTramites.hasClass('tramites-digitalizados')?311:null)
            }
        });
        $('#instituciones').on('change', function(e){
            $('#formSigueElAvance').find('#offset').val(0);
            $('#formSigueElAvance').submit();
            e.preventDefault();
        });
        $(".btn-filtro")
            .on('click', function(e){
                var elem = $(this),
                    ascendente = elem.data('direccion') === 1,
                    ordenamiento = elem.data('filtro'),
                    icono = $('<i class="'+(!ascendente?"icon-arrow-down":"icon-arrow-up")+'"></i>');
                $('#formSigueElAvance').find('#orderby').val(ordenamiento);
                $('#formSigueElAvance').find('#orderdir').val(ascendente?'ASC':'DESC');
                $(".btn-filtro").find('i').remove();
                elem.append(icono);
                elem.data('direccion', !ascendente);
                if($('.pagination').length){
                    $('#formSigueElAvance').submit();
                }else{
                contTramites.isotope({
                    sortBy : ordenamiento,
                    sortAscending : !ascendente
                });
                }
                
            });
        if(contTramites.hasClass('tramites-digitalizados')){
            contTramites.on('click', tramites.selector, function(e){
                var tramite = $(this),
                    tramite_activo = tramites.filter('.active');

                if(!tramite.hasClass('active')){
                    tramite.addClass('active');
                        tramite.find('.mas-info').show();
                        contTramites.isotope('reLayout');
                }
                if(tramite_activo.length){
                    tramite_activo.removeClass('active');
                    tramite_activo.find('.mas-info').hide();
                    contTramites.isotope('reLayout');
                }
            });
        }else{
            tramites.on('click', function(e){
                var elem = $(this);
                if(elem.hasClass('digitalizado')){
                    if(elem.data('url-chileatiende')){
                        window.open(elem.data('url-chileatiende'));
                    }
                    return false;
                }
                if(!elem.hasClass('large')){
                    tramites.filter('.large').removeClass('large');
                    elem.addClass('large');

                    var _top = $(document).scrollTop()-contTramites.offset().top+100,
                        _left = 115;

                    if(Modernizr.csstransforms3d){
                        elem.data('prev-translate', elem.css('translate'));
                        elem.css({
                            'translate':[_left,_top]
                        });
                    }else{
                        elem.data('prev-left', elem.css('left')).data('prev-top', elem.css('top'));
                        elem.css({
                            'left':_left,
                            'top':_top
                        });
                    }
                    elem.find('.mas-info').fadeTo(600,1);
                    tramitesOverlay.fadeTo(800,0.6, function(){
                        drawChartCanales(elem);
                        drawChartRazones(elem);
                    });
                }
                e.preventDefault();
            });
            tramitesOverlay.on('click', function(e){
                cerrarMasInfoTramite(contTramites, tramites, tramitesOverlay);
                e.preventDefault();
            });
            contTramites.on('click', '.cerrar-tramite', function(){
                cerrarMasInfoTramite(contTramites, tramites, tramitesOverlay);
            });
        }
        //Chrome fix
        setTimeout(function(){
            contTramites.isotope('reLayout');
        },500);
    }
});
function cerrarMasInfoTramite(contTramites, tramites, tramitesOverlay){
    var elem = tramites.filter('.large');
        if(Modernizr.csstransforms3d){
            elem.css('translate', elem.data('prev-translate'));
        }else{
            elem.css({
                left:elem.data('prev-left'),
                top:elem.data('prev-top')
            });
        }
        elem.removeClass('large');
        elem.find('.mas-info').hide();
        elem.find('.contenedor-grafico-canales').html('');
        elem.find('.contenedor-grafico-razones').html('');
        tramitesOverlay.fadeTo(1000,0, function(){
            tramitesOverlay.hide();
            contTramites.isotope('reLayout');
        });
}
function drawChartCanales(elem) {
  var data = google.visualization.arrayToDataTable([
    ['Task', 'Canales'],
    ['Oficina', elem.data('oficina')],
    ['En linea', elem.data('online')],
    ['Correo', elem.data('correo')],
    ['Teléfono', elem.data('telefono')],
    ['No definido',    elem.data('nodefinido')]
  ]);

  var options = {
    title: 'Canales',
    chartArea: { left:0,top:30,width:"100%",height:"300px" }
  };

  var chart = new google.visualization.PieChart(elem.find('.contenedor-grafico-canales')[0]);
  chart.draw(data, options);
}
function drawChartRazones(elem){
    var data = google.visualization.arrayToDataTable([
    ['Task', 'Razones'],
    ['Trámite innecesario', elem.data('razon-1')],
    ['Trámite demasiado largo de realizar', elem.data('razon-2')],
    ['Tiempo de respuesta muy largo', elem.data('razon-3')],
    ['Solicita demasiados papeles', elem.data('razon-4')],
    ['Solicita papeles que ya están en poder del Estado', elem.data('razon-5')],
    ['Filas muy largas para realizar el trámite', elem.data('razon-6')],
    ['Pocas oficinas donde realizarlo', elem.data('razon-7')],
    ['Debe ir a más de una oficina para realizar el trámite', elem.data('razon-8')],
    ['Debe ir más de una vez a la oficina para realizar el trámite', elem.data('razon-9')]
  ]);

  var options = {
    title: 'Razones',
    chartArea: { left:0,top:30,width:"100%",height:500 }
  };

  var chart = new google.visualization.PieChart(elem.find('.contenedor-grafico-razones')[0]);
  chart.draw(data, options);
}
function numericVal(keyCode){
    return (keyCode > 47 && keyCode < 58)||(keyCode > 95 && keyCode < 106)||(keyCode==8||keyCode==46||keyCode==9)||(keyCode > 36 && keyCode < 41);
}

var Avance = {
    init : function () {
        Avance.cacheElements();
        Avance.loadPlugins();
        Avance.bindEvents();
        if(location.hash)
            $(location.hash).trigger('click');

        return this;
    },
    cacheElements : function () {
        Avance.tablaServicios = $('.tabla-servicios');
        Avance.siteUrl = $('#site_url').val();
        Avance.listPorcInstituciones = $('.cont-listado-porcentaje-instituciones');
        Avance.contInstitucion = $('.cont-institucion');
    },
    bindEvents : function () {
        Avance.listPorcInstituciones.on('click', 'h2', function (e) {
            var elem = $(this),
                seccion = elem.parents('.cont-instituciones-porcentaje');
            if(!seccion.hasClass('active')){
                Avance.desactivaSeccionActivaPorcInstituciones();
                Avance.activaSeccionPorcInstituciones(seccion);
            }else{
                Avance.desactivaListadoPorcInstituciones();
            }
            e.preventDefault();
        });
        Avance.contInstitucion.on('click', '.encabezado-institucion', function (e) {
            var elem = $(this),
                container = elem.parents('.cont-institucion'),
                tramites = container.find('.tramites-institucion');
            if(container.hasClass('active')){
                tramites.slideUp();
                container.removeClass('active');
            }else{
                Avance.contInstitucion.filter('.active').find('.tramites-institucion').slideUp();
                Avance.contInstitucion.filter('.active').removeClass('active');
                container.addClass('active');
                tramites.slideDown();
            }
            e.preventDefault();
        });
    },
    desactivaSeccionActivaPorcInstituciones : function () {
        Avance.listPorcInstituciones.find('.cont-instituciones-porcentaje.active').removeClass('active').animate({
            'height' : '0px'
        });
    },
    activaSeccionPorcInstituciones : function (seccion) {
        var cont = seccion.find('.instituciones-porcentaje');
        seccion.addClass('active').animate({
            'height' : cont.outerHeight()+'px'
        });
        seccion.find('h2').animate({
            'paddingTop' : 0
        })
        Avance.activaListadoPorcInstituciones();
    },
    desactivaListadoPorcInstituciones : function () {
        Avance.listPorcInstituciones.find('.cont-instituciones-porcentaje.active').each(function () {
            Avance.desactivaSeccionActivaPorcInstituciones($(this));
        });
        Avance.listPorcInstituciones.removeClass('.active');
        Avance.listPorcInstituciones.find('.cont-instituciones-porcentaje').animate({
            'minHeight' : '40px'
        });
        Avance.listPorcInstituciones.find('.cont-instituciones-porcentaje h2').animate({
            'paddingTop' : 0
        });
    },
    activaListadoPorcInstituciones : function () {
        Avance.listPorcInstituciones.addClass('active');
        Avance.listPorcInstituciones.find('.cont-instituciones-porcentaje').each(function () {
            var elem = $(this);
            if(!elem.hasClass('active')){
                elem.animate({
                    'minHeight' : '20px'
                });
                elem.find('h2').animate({
                    'paddingTop' : '20px'
                });
            }
        });
    },
    loadPlugins : function () {
        if(Avance.tablaServicios.length){
            Avance.acordeonServicios.init();
        }
        if($('[data-toggle="chosen"]').length){
            Avance.buscadorInstituciones.init();
        }
    },
    buscadorInstituciones : {
        init : function () {
            this.formBusqueda = $('.form-buscar-institucion');
            this.selectInstituciones = this.formBusqueda.find('[data-toggle="chosen"]');
            this.initChosen();
            this.bindEvents();
            return this;
        },
        initChosen : function () {
            if(location.hash){
                this.selectInstituciones.val(location.hash.replace('#',''));
            }
            this.selectInstituciones.chosen();
        },
        bindEvents : function () {
            var self = this;
            this.formBusqueda.on('submit', function (e) {
                var link = self.selectInstituciones.find('option:selected').data('link');
                if(link){
                    window.location = link;
                }
                e.preventDefault();
            });
            $(window).hashchange(function () {
                if(Avance.contInstitucion.length){
                    var codigo_servicio = location.hash;
                    Avance.contInstitucion.find(codigo_servicio).click();
                }
            });
        }
    },
    acordeonServicios : {
        init : function(){
            this.bindEvents();
        },
        bindEvents : function(){
            Avance.tablaServicios.on('click', '.table-servicios-heading', function(e){
                var heading = $(this),
                    activeHeading = Avance.tablaServicios.find('[data-heading-id="'+Avance.tablaServicios.data("active-heading")+'"]');
                if(Avance.tablaServicios.data('active-heading') == heading.data('heading-id')){
                    heading.trigger('toggle-heading', false);
                    Avance.tablaServicios.data('active-heading', null);
                }else{
                    if(activeHeading.length)
                        activeHeading.trigger('toggle-heading', false);

                    heading.trigger('toggle-heading', true);
                    Avance.tablaServicios.data('active-heading', heading.data('heading-id'));
                }
                e.preventDefault();
            });
            Avance.tablaServicios.on('toggle-heading', '.table-servicios-heading', function(e, active){
                elem = $(this);
                if(active){
                    elem.addClass('active').next('.table-servicios-body').slideDown();
                }else{
                    elem.removeClass('active').next('.table-servicios-body').slideUp();
                }
            });
        }
    }

};