(function($){
	$(function(){
		$('.modal-content').on('click', function(e){
			var href = $(this).attr('href');
			$.get(href, function(data){
				$('#modal-container .modal-body').html(data);
				$('#modal-container').modal();
			});
			e.preventDefault();
		});
		var sortTable = $('.sort-table');
		if(sortTable.length){
			var filtros = sortTable.find('thead th.sort-field'),
				form = $('#'+(sortTable.data('form-filter')?sortTable.data('form-filter'):'filtros_resultados')),
				sortField = $('#sort-field').val(),
				sortDir = $('#sort-direction').val();
			filtros
				.css('cursor', 'pointer')
				.on('click', function(e){
					var elem = $(this);
					$('#sort-field').val($(this).data('filter-name'));
					if(!elem.data('sort-direction') || elem.data('sort-direction') == 'DESC'){
						$('#sort-direction').val('ASC');
					}else{
						$('#sort-direction').val('DESC');
					}
					$('#exportar').val('');
					form.submit();
					e.preventDefault();
				});
			filtros.each(function(i, elem){
				var filtro = $(elem),
					icon = 'icon-resize-vertical';
				if(filtro.data('filter-name') == sortField){
					icon = sortDir=='DESC'?'icon-arrow-down':'icon-arrow-up';
					filtro.data('sort-direction', sortDir);
				}
				filtro.append('<i class="'+icon+'"><i>');
			});
			/*Busequeda*/
			$('.filtro-busqueda').on('change', function(){
				$('#exportar').val('');
				form.submit();
			});
			/*Exportar a excel*/
			$('#btn-exportar').on('click', function(e){
				$('#exportar').val('s');
				form.submit();
				e.preventDefault();
			});
			$('#btn-filtrar').on('click', function(e){
				$('#exportar').val('');
				form.submit();
				e.preventDefault();
			});
		}
        var frmTramite = $('.form-tramite');
        if(frmTramite.length){
            frmTramite.on('click', '#trae-info-ficha-chileatiende', function(e){
                var codigo = frmTramite.find('#codigo');
                if(!codigo.val()){
                    codigo.addClass('error');
                }else{
                    var urlApi = 'https://www.chileatiende.cl/api/fichas/'+codigo.val()+'?access_token=jYmvOP5znSfNRocw&callback=?';
                    $.getJSON(urlApi).success(function(data){
                        console.log(data.ficha);
                        frmTramite.find('#nombre').addClass('updated').val(data.ficha.titulo);
                        frmTramite.find('#institucion').addClass('updated').val(data.ficha.servicio);
                        frmTramite.find('#digitalizado').addClass('updated').val(data.ficha.guia_online_url?1:0);
                        frmTramite.find('#url').addClass('updated').val(data.ficha.permalink);
                        frmTramite.find('#link-chileatiende').attr('href',data.ficha.permalink);
                    });
                }
                e.preventDefault();
            }).on('click', '#link-chileatiende', function(e){
                var link = $(this);
                if(!link.attr('href')){
                    e.preventDefault();
                }
            });
        }
	});
})(jQuery);