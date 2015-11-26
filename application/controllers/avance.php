<?php
class Avance extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->data['filtros_ordenamiento'] = array('porc_digitalizados' => 'Porcentaje de digitalización', 'cant_tramites' => 'Trámites comprometidos', 'nombre' => 'Alfabéticamente');
        $this->data['title'] = 'Avance';
        $this->data['options']['orderby'] = $this->input->get('orderby')?$this->input->get('orderby'):'porc_digitalizados';
        $this->data['options']['orderdir'] = $this->input->get('orderdir')?$this->input->get('orderdir'):'DESC';
        $this->data['active_menu'] = 'avance';
        $this->load->model('Avance_model');
        $this->data['options']['rangos'] = $this->Avance_model->getRangos();
        
        setlocale(LC_TIME, "es_CL.utf8");
    }
    public function index()
    {
        $this->data['instituciones_agrupadas'] = array();
        $this->data['options']['orderby'] = 'nombre';
        $this->data['options']['orderdir'] = 'ASC';
        $this->data['instituciones_agrupadas'] = $this->Avance_model->getInstitucionPorPorcentajeAvance($this->data['options']);

        $this->data['content'] = $this->load->view('avance_tramites/instituciones', $this->data, true);
        $this->load->view('template-avance', $this->data);
        $this->output->cache(5);
    }

    public function tramo($tramo = 0)
    {
        $this->data['options']['orderby'] = 'nombre';
        $this->data['options']['orderdir'] = 'ASC';
        $this->data['instituciones_agrupadas'] = $this->Avance_model->getInstitucionPorPorcentajeAvance($this->data['options']);

        $this->data['options']['tramo'] = $tramo;
        $instituciones = $this->Avance_model->getInstitucionPorPorcentajeAvance($this->data['options']);
        $total_tramites = 0;

        foreach ($instituciones as $key => $institucion) {
            $this->data['options']['codigo_servicio'] = $institucion->codigo;
            $this->data['options']['orderby'] = 'cumplido';
            $this->data['instituciones'][$key] = $institucion;
            $this->data['instituciones'][$key]->tramites = $this->Avance_model->getTramitesServicio($this->data['options']);
            $total_tramites += $institucion->cant_tramites;
        }

        $this->data['total_tramites'] = $total_tramites;

        $this->data['tramo'] = $tramo;
        $this->data['content'] = $this->load->view('avance_tramites/tramo', $this->data, true);
        $this->load->view('template-avance', $this->data);
        $this->output->cache(5);
    }

    public function actualizaDatosTramite()
    {
        $info_tramites= $this->Avance_model->actualizaDatosTramite();
        $this->load->view('avance_tramites/info_actualizacion', array('content' => $info_tramites));
        $this->output->cache(5);
    }
}
?>