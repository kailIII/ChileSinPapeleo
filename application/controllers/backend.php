<?php

Class Backend extends CI_Controller {

        function __construct(){
            parent::__construct();
        }

    public function index() {
        redirect('backend/resultados', 'location', 301);
    }

    public function paginas() {
        $this->load->model('Pagina');
        $paginas = $this->Pagina->getPaginas();

        $aData['vista'] = 'backend/paginas';
        $aData['paginas'] = $paginas;

        $this->load->view('template-backend', $aData);
    }

    public function editar($amigable) {
        $this->load->model('Pagina');
        $pagina = $this->Pagina->getPagina($amigable);
        
        $aData['vista'] = 'backend/pagina/form';
        $aData['pagina'] = $pagina;
        $aData['form_action'] = 'backend/actualizar/'.$pagina->id;
        
        $this->load->view('template-backend', $aData);
    }
    
    public function actualizar($idPagina) {
        $this->load->model('Pagina');
        $this->Pagina->updatePagina($idPagina);
        
        redirect('backend/paginas');
    }
    
    public function agregar() {
        $aData['vista'] = 'backend/pagina/form';
        $aData['form_action'] = 'backend/guardar';
        
        $this->load->view('template-backend', $aData);
    }
    
    public function guardar() {
        $this->load->model('Pagina');
        $this->Pagina->insertPagina();
        
        redirect('backend/paginas');
    }

    public function resultados($action = 'listar'){

        $this->load->library('session');
        $this->load->library('pagination');

        $offset = $this->input->get('offset')?intval($this->input->get('offset')):0;
        $limit = 10;

        //Filtros de busqueda
        $filtrar = $this->input->post('filtrar', true);
        $aData['solo_mejoras'] = $action=='mejoras';

        if($filtrar == 1){
            $aData['tipo'] = $this->input->post('tipo', true);
            $aData['razon'] = $this->input->post('razon', true);
            $aData['institucion'] = $this->input->post('institucion', true);
            $aData['sort_field'] = $this->input->post('sort-field', true);
            $aData['sort_direction'] = $this->input->post('sort-direction', true);
            $aData['exportar'] = $this->input->post('exportar', true);
            $aData['canal'] = $this->input->post('canal', true);
            $aData['tipo_denuncia'] = $this->input->post('tipo_denuncia', true);
            $aData['origen'] = $this->input->post('origen', true);
        }else{
            $aData['tipo'] = $this->session->userdata('tipo');
            $aData['razon'] = $this->session->userdata('razon');
            $aData['institucion'] = $this->session->userdata('institucion');
            $aData['sort_field'] = $this->session->userdata('sort_field');
            $aData['sort_direction'] = $this->session->userdata('sort_direction');
            $aData['canal'] = $this->session->userdata('canal');
            $aData['tipo_denuncia'] = $this->session->userdata('tipo_denuncia');
            $aData['origen'] = $this->session->userdata('origen');
            $aData['exportar'] = null;
        }

        if(!$aData['sort_field']){
            $aData['sort_field'] = 'id';
            $aData['sort_direction'] = 'DESC';
        }

        $this->load->model('Formulario_model');

        //Obtiene el total de registros
        if($aData['exportar']){
            ini_set('memory_limit', '1024M');
            $resultados = $this->Formulario_model->getResultados(false, $aData);

            $aData['resultados'] = $resultados;
            $this->load->view('backend/resultados/excel', $aData);
        }else{
            $total_resultados = $this->Formulario_model->getResultados(true, array('solo_mejoras' => $aData['solo_mejoras']));
            $total = $this->Formulario_model->getResultados(true, $aData);
            $resultados = $this->Formulario_model->getResultados(false, $aData, $limit, $offset);

            $aData['razones'] = $this->Formulario_model->getRazones();
            $aData['instituciones'] = $this->Formulario_model->getInstituciones(false, 't.institucion', 'ASC', null, $aData['solo_mejoras']);

            if($aData['institucion']){
                $aData['tramites'] = $this->Formulario_model->getTramites(false, array('institucion' => md5($aData['institucion']), 'solo_mejoras' => $aData['solo_mejoras']), array('tramite' => 'asc'), 10000, 0);
            }

            $aData['vista'] = 'backend/resultado';
            $aData['total_resultados'] = $total_resultados;
            $aData['total'] = $total;
            $aData['resultados'] = $resultados;

            $this->pagination->initialize(array(
                'base_url' => base_url('backend/resultados?'),
                'total_rows' => $total,
                'per_page' => $limit,
                'cur_tag_open' => '<a>',
                'cur_tag_close' => '</a>',
                'num_links' => 5
            ));

            $aData['pagination'] = $this->pagination->create_links();

            $this->session->set_userdata('tipo', $aData['tipo']);
            $this->session->set_userdata('razon', $aData['razon']);
            $this->session->set_userdata('institucion', $aData['institucion']);
            $this->session->set_userdata('sort_field', $aData['sort_field']);
            $this->session->set_userdata('sort_direction', $aData['sort_direction']);

            $this->load->view('template-backend', $aData);
        }
    }

    public function ver_resultado($id){
        $this->load->model('Formulario_model');

        $resultado = $this->Formulario_model->getResultado($id);

        $servicios = $this->Formulario_model->getServiciosRelacionados();

        $resultado->nombre_servicios_relacionados = $this->Formulario_model->idServiciosToNombre($resultado->servicios_relacionados);

        $this->load->view('backend/resultados/ver', $resultado);
    }

    public function get_nombres_chileatiende(){
        $this->load->model('Formulario_model');

        $origenes = $this->Formulario_model->getOrigenesResultados(array(
                "sin_institucion" => true,
                "sin_tramite" => true
            ));
        foreach ($origenes as $key => $origen) {
               $info_ficha = $this->Formulario_model->getInfoFicha($origen->origen);
            $this->Formulario_model->actualizaInfoResultado($origen->origen, $info_ficha);
            echo '<p>Codigo: '.$origen->origen.', Procesado</p>';
            sleep(1);
        }
    }

    //Tramites de chilesinpapeleo
    public function tramites($action = 'listar', $codigo = null)
    {
        if($codigo)
            call_user_func(array($this, 'tramites_'.$action), $codigo);
        else
            call_user_func(array($this, 'tramites_'.$action));
    }

    public function tramites_listar()
    {
        $this->load->model('Formulario_model');

        $this->load->library('pagination');

        $options = array();

        $institucion = $this->input->get('instituciones')?$this->input->get('instituciones'):null;
        $orderby = $this->input->get('orderby')?$this->input->get('orderby'):'tramite';
        $orderdir = $this->input->get('orderdir')?$this->input->get('orderdir'):'ASC';
        $tramite = $this->input->get('tramite')?$this->input->get('tramite'):'';
        $codigo = $this->input->get('codigo')?$this->input->get('codigo'):'';
        $digitalizados = $this->input->get('digitalizados')?$this->input->get('digitalizados'):'';
        $sello_chilesinpapeleo = $this->input->get('sello_chilesinpapeleo')?$this->input->get('sello_chilesinpapeleo'):'';

        if($institucion)
            $options['institucion'] = $institucion;

        if($tramite)
            $options['tramite'] = $tramite;

        if($codigo)
            $options['codigo'] = $codigo;

        if($sello_chilesinpapeleo)
            $options['sello_chilesinpapeleo'] = $sello_chilesinpapeleo;

        if($digitalizados)
            $options['digitalizados'] = $digitalizados;

        $aData['total'] = $this->Formulario_model->getTramites(true, $options);
        $aData['limit']  = 20;
        $aData['tramite'] = $tramite;
        $aData['codigo'] = $codigo;
        $aData['digitalizados'] = $digitalizados;
        $aData['sello_chilesinpapeleo'] = $sello_chilesinpapeleo;
        $aData['offset']  = $this->input->get('offset')?$this->input->get('offset'):0;
        $aData['tramites'] = $this->Formulario_model->getTramites(false, $options, array($orderby => $orderdir), $aData['limit'], $aData['offset']);

        $aData['orderby'] = $orderby;
        $aData['orderdir'] = $orderdir;
        $aData['selectedInstitucion'] = $institucion;
        $aData['max_denuncias'] = $this->Formulario_model->getMaxDenuncias();
        $aData['total_instituciones'] = $this->Formulario_model->getInstitucionesBackend(array('total' => true));
        $aData['instituciones'] = $this->Formulario_model->getInstitucionesBackend(array('total' => false, 'orderby' => 'institucion', 'orderdir' => 'ASC'));
        $aData['razones'] = $this->Formulario_model->getRazones();
        $aData['total_denuncias'] = $this->Formulario_model->getResultados(true);

        $pagination_config['base_url'] = site_url('backend/tramites?orderby='.$orderby.'&orderdir='.$orderdir.($institucion?'&instituciones='.$institucion:'').($tramite?'&tramite='.$tramite:'').($codigo?'&codigo='.$codigo:''));
        $pagination_config['total_rows'] = $aData['total'];
        $pagination_config['per_page'] = $aData['limit'];

        $this->pagination->initialize($pagination_config);

        $aData['pagination'] = $this->pagination->create_links();
        $aData['vista'] = 'backend/tramites/listar';
        //$contenido->contenido = $this->load->view('backend/tramites/listar', $aData, true);

        $this->load->view('template-backend', $aData);
    }

    public function tramites_editar($id = null)
    {
        $this->load->model('Formulario_model');

        $aData['tramite'] = $this->Formulario_model->getTramite($id);
        $aData['vista'] = 'backend/tramites/form';

        $this->load->view('template-backend', $aData);
    }

    public function tramites_nuevo()
    {
        $this->load->model('Formulario_model');

        $tramite['id'] = '';
        $tramite['nombre'] = '';
        $tramite['institucion'] = '';
        $tramite['url'] = '';
        $tramite['codigo'] = '';
        $tramite['digitalizado'] = '';
        $tramite['digitalizacion_proactiva'] = '';

        $aData['tramite'] = (object)$tramite;
        $aData['vista'] = 'backend/tramites/form';

        $this->load->view('template-backend', $aData);
    }

    public function tramites_grabar($id = null)
    {
        $this->load->model('Formulario_model');

        $tramite['nombre'] = $this->input->post('nombre', true)?$this->input->post('nombre', true):'';
        $tramite['institucion'] = $this->input->post('institucion', true)?$this->input->post('institucion', true):'';
        $tramite['url'] = $this->input->post('url', true)?$this->input->post('url', true):'';
        $tramite['sello_chilesinpapeleo'] = $this->input->post('sello_chilesinpapeleo', true)?$this->input->post('sello_chilesinpapeleo', true):0;
        $tramite['digitalizado'] = $this->input->post('digitalizado', true)?$this->input->post('digitalizado', true):0;
        $tramite['digitalizacion_proactiva'] = $this->input->post('digitalizacion_proactiva', true)?$this->input->post('digitalizacion_proactiva', true):0;
        $tramite['codigo'] = $this->input->post('codigo', true)?$this->input->post('codigo', true):'';
        $tramite['id'] = $id;

        //Verificamos que no exista un trámite distinto con el mismo codigo
        $tramite_duplicado = $this->Formulario_model->getTramiteDuplicado($tramite['codigo'], $id);

        if(!$tramite['nombre'] || !$tramite['codigo']){
            $aData['error'] = true;
            $aData['error_msg'] = 'Debe ingresar el nombre y codigo del tramite.';
            $aData['tramite'] = (object)$tramite;
            $aData['vista'] = 'backend/tramites/form';
        }elseif($tramite_duplicado){
            $aData['error'] = true;
            $aData['error_msg'] = 'El código de trámite ya existe.';
            $aData['tramite'] = (object)$tramite;
            $aData['vista'] = 'backend/tramites/form';
        }else{
            //Si viene el codigo como parámetro, se está actualizando el trámite.
            if($id){
                $this->Formulario_model->actualizaTramite($tramite);
            }else{
                $this->Formulario_model->grabaTramite($tramite);
            }

            redirect('backend/tramites');
        }

        $this->load->view('template-backend', $aData);
    }

    public function actualizar_datos_chileatiende($codigo = null)
    {
        $this->load->model('Formulario_model');

        if(!$codigo){
            $tramites = $this->Formulario_model->getTramites(false, array(), null, 1500, 0);
            foreach($tramites as $tramite){
                $infoFicha = $this->Formulario_model->getInfoFicha($tramite->origen);
                if($infoFicha){
                    $tramite_actualizado = array(
                            'codigo' => $tramite->origen,
                            'nombre' => stringsHelper::fixUtf8($infoFicha['titulo']),
                            'institucion' => stringsHelper::fixUtf8($infoFicha['servicio']),
                            'url' => $tramite->url_chileatiende,
                            'digitalizado' => $tramite->digitalizado,
                            'digitalizacion_proactiva' => $tramite->digitalizacion_proactiva,
                            'id' => $tramite->id_tramite
                        );
                    $this->Formulario_model->actualizaTramite($tramite_actualizado);
                }
            }
        }
    }

    public function actualiza_tipo_denuncia_resultados()
    {
        $this->load->model('Formulario_model');

        $tramites = $this->Formulario_model->getTramites(false, array('fecha_publicacion_guia_online' => true), null, 1500, 0);
        $count = 1;
        foreach ($tramites as $key => $tramite) {
            //Actualiza los resultados según la fecha de publicación guia online
            $this->Formulario_model->actualizaTipoDenunciaResultadosTramite($tramite);
        }
    }
}

?>
