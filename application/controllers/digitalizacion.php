<?php
	/**
	* 
	*/
	class Digitalizacion extends CI_Controller{

		var $postData = null;
		var $errorMsg = '';
		
		function __construct(){
			parent::__construct();

			
          $this->aData['active_menu'] = '';
          $this->aData['title'] = 'Formulario de denuncia';

          $this->aData['tamano_empresa'] = '';
          $this->aData['etapa_empresa'] = null;
          $this->aData['servicios_relacionados'] = array();
		}

		public function index(){
			redirect('digitalizacion/formulario');
		}

		public function formulario(){

			$this->load->model('Formulario_model');

			if($this->errorMsg == ''){
				$this->aData['tipo'] = 'p';
				$this->aData['origen'] = $this->input->get('origen', true);
				$this->aData['tipo_tramite'] = $this->input->get('tipo_tramite', true);
                $this->aData['mejora'] = $this->input->get('mejora', true)?$this->input->get('mejora', true):'0';
			}else{
				$this->aData['errorMsg'] = $this->errorMsg;
			}

			$this->aData['actividades'] = $this->Formulario_model->getActividades();
			$this->aData['razones'] = $this->Formulario_model->getRazones();
			$this->aData['checks_servicios_relacionados'] = $this->Formulario_model->getServiciosRelacionados();
			$this->aData['etapas_empresa'] = $this->Formulario_model->getEtapasEmpresa();

			$infoFicha = $this->Formulario_model->getInfoFicha($this->aData['origen']);

			if((!$infoFicha && $this->aData['tipo'] != 'e') || ($this->aData['tipo'] != 'e' && $this->aData['tipo'] != 'p'))
				redirect('/');

			$this->setTitulo();

			$this->aData['texto_institucion'] = isset($infoFicha['servicio'])?$infoFicha['servicio']:false;
			$this->aData['texto_tramite'] = isset($infoFicha['titulo'])?$infoFicha['titulo']:false;
            $this->aData['url_tramite'] = isset($infoFicha['permalink'])?$infoFicha['permalink']:'';

			$this->aData['stuff'] = $this->load->view('digitalizacion_formulario', $this->aData, true);

			$this->load->view('template-interior', $this->aData);
		}

		public function enviar_formulario(){

			$this->load->model('Formulario_model');

			$this->aData = array_merge($this->aData, $this->getPostData());

			if($this->Formulario_model->graba_formulario($this->aData)){
				redirect('digitalizacion/gracias');
			}else{
				$this->formulario();
			}

		}

		public function gracias(){

			$this->aData['photobooth'] = '<section class="row photoBooth">
                <div class="span12">
                    <header class="page-header">
                        <h1>
                            Gracias por colaborar en la campaña Chile sin papeleo.
                        </h1>
                    </header>
                </div>
            </section>';

			$this->aData['stuff'] = $this->load->view('gracias_formulario', $this->aData, true);

			$this->load->view('template-interior', $this->aData);
		}

		public function getPostData(){
			foreach($_POST as $field_name => $field){
				$this->postData[$field_name] = $this->input->post($field_name, true);
			}
			return $this->postData;
		}

		public function setTitulo(){
			if ($this->aData['tipo'] == 'e') {
				$titulo = 'Empresas y Organizaciones';
				$bajada = '';
				$this->aData['introduccion'] = 'Cuéntanos qué trámite está afectando tu emprendimiento en cualquiera de sus etapas';
			}else{
				$titulo = 'Personas';
				$bajada = '';
				$this->aData['introduccion'] = 'Cuéntanos como podríamos mejorar o digitalizar este trámite para simplificarte la vida';
			}

			$this->aData['photobooth'] = '<section class="row photoBooth">
                <div class="span12">
                    <header class="page-header">
                        <h1>
                            '.$titulo.'
                            <small>'.$bajada.'</small>
                        </h1>
                    </header>
                </div>
            </section>';
		}
	}
?>