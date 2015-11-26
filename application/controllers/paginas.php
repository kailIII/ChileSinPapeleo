<?php

class Paginas extends CI_Controller {

    var $titulo = '';
    var $amigable = '';
    var $subtitulo = '';
    var $contenido = '';

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function index() {
        redirect("http://www.modernizacion.gob.cl/"); 
    }

    public function index_old() {
        $this->load->model('Formulario_model');
        $total_digitalizados = 577;//$this->Formulario_model->getTotalDigitalizados();
        $total_peticiones_digitalizadas = 28785;//$this->Formulario_model->getCantidadPeticionesDigitalizadas();
        $total_con_peticiones = 137;//$this->Formulario_model->getCantidadTramitesConPeticiones();
        $total_instituciones = $this->Formulario_model->getInstituciones(true);
        $total_denuncias = $this->Formulario_model->getResultados(true);
        $aData['active_menu'] = 'portada';

        $aData['carousel'] = '<section class="row imageBox">
                <div class="span12">
                    <!-- carousel en portada -->
                    <div class="carousel slide" id="homeCarousel">
                        <!-- Carousel items -->
                        <div class="carousel-inner">
                            <div class="item item-avance">
                                <img alt="" src="' . site_url('assets/img/carousel/normal/banner_avance.png') . '">
                                <div class="carousel-caption align-right">
                                    <h1>¿Cómo avanza el Estado<br>en digitalizar tus trámites?</h1>
                                    <p><a href="' . site_url('avance') . '" class="btn btn-success btn-large">Revísalo aquí</a></p>
                                </div>
                            </div>
                            <div class="item active item-info-digitalizaciones">
                                <div class="info-participaciones pagination-centered">
                                    Hemos recibido <span>'.number_format($total_denuncias,0,",",".").'</span> peticiones ciudadanas para <span>'.$total_instituciones.'</span> instituciones
                                </div>
                                <div class="cont-resultados">
                                    <div class="cont-resultado cont-tramites-digitalizados">
                                        <div class="cont-cant-tramites">
                                            <div class="cant-tramites-digitalizados">
                                                <span>'.$total_digitalizados.'</span>
                                            </div>
                                        </div>
                                        <h3>trámites digitalizados</h3>
                                    </div>
                                    <img src="'.base_url('assets/img/sigue_el_avance/signo_igual.png').'" class="signo_igual">
                                    <div class="cont-resultado cont-tramites-votados">
                                        <div class="cont-cant-tramites">
                                            <div class="cant-tramites-votados">
                                                <span>'.$total_con_peticiones.'</span>
                                            </div>
                                        </div>
                                        <h3>en respuesta a '.$total_peticiones_digitalizadas.' peticiones</h3>
                                        <a href="'.site_url('paginas/ver/sigue-el-avance').'" class="btn btn-primary">Más Información</a>
                                    </div>
                                    <img src="'.base_url('assets/img/sigue_el_avance/signo_mas.png').'" class="signo_mas">
                                    <div class="cont-resultado cont-tramites-proactivos">
                                        <div class="cont-cant-tramites">
                                            <div class="cant-tramites-proactivos">
                                                <span>'.($total_digitalizados-$total_con_peticiones).'</span>
                                            </div>
                                        </div>
                                        <h3>digitalizados proactivamente</h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="item">
                                <img alt="" src="' . site_url('assets/img/carousel/normal/1.png') . '">
                                <div class="carousel-caption align-right">
                                    <h1>Chile sin papeleo</h1>
                                    <p>Usa tu tiempo en lo que más quieras</p>
                                    <p><a href="' . site_url('paginas/ver/acerca-de-esta-campana') . '" class="btn btn-success btn-large">Más Información</a></p>
                                </div>
                            </div>
                            <div class="item">
                                <img alt="" src="' . site_url('assets/img/carousel/normal/3.png') . '">
                                <div class="carousel-caption">
                                    <h1>Ejerce tus derechos</h1>
                                    <p>Conoce la Ley Nº19.880</p>
                                    <p><a href="' . site_url('paginas/ver/conoce-tus-derechos-y-deberes') . '" class="btn btn-success btn-large">Más Información</a></p>
                                </div>
                            </div>
                            <div class="item">
                                <img alt="" src="' . site_url('assets/img/carousel/normal/4.png') . '">
                                <div class="carousel-caption align-right">
                                    <h1>¿Obstáculos para emprender?</h1>
                                    <p>Cuéntanos qué trámite está afectando tu emprendimiento</p>
                                    <p><a href="' . site_url('digitalizacion/formulario') . '" class="btn btn-success btn-large">Más Información</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- Carousel nav -->
                        <a data-slide="prev" href="#homeCarousel" class="carousel-control left">‹</a>
                        <a data-slide="next" href="#homeCarousel" class="carousel-control right">›</a>
                    </div>
                </div>
            </section>';

        $aData['feature'] = '<section class="row featured">
                <!-- four nice small boxes filled with important things -->
                <div class="span12">
                    <header class="page-header">
                        <h2>
                            Chile sin papeleo
                            <small>usa tu tiempo en lo que más quieras</small>
                        </h2>
                    </header>
                </div>
                <div class="span6 circulo">
                    <i class="box"></i>
                    <h3><a href="' . site_url('paginas/ver/acerca-de-esta-campana') . '">Acerca de esta campaña</a></h3>
                    <p>¿Filas eternas?¿te mandaron a otra oficina?¿perdiste toda la mañana? Participa y ayúdanos a simplificar los trámites del Estado</p>
                </div>
                <div class="span6 circulo">
                    <i class="gear"></i>
                    <h3><a href="' . site_url('paginas/ver/como-participar') . '">Cómo participar</a></h3>
                    <p>Tú haces los trámites. Queremos conocer tu experiencia para entregarte mejores servicios a partir del 16 de Agosto</p>
                </div>
                <div class="span6 circulo">
                    <i class="eye"></i>
                    <h3><a href="' . site_url('paginas/ver/conoce-tus-derechos-y-deberes') . '">Conoce tus derechos</a></h3>
                    <p>Infórmate y exígenos una mejor atención para que podamos seguir mejorando</p>
                </div>
                <div class="span6 circulo">
                    <i class="case"></i>
                    <h3>Sigue el avance</h3>
                    <p>En este sitio podrás seguir en línea el cumplimiento de los compromisos de simplificación de los trámites del Estado</p>
                </div>
            </section>';

        $aData['story'] = '<section class="row story">
                <div class="span12">
                    <header class="page-header">
                        <h2>
                            Cómo participar
                            <small>cuéntanos cómo podemos mejorar</small>
                        </h2>
                    </header>
                </div>
            </section>';

        $aData['content'] = '<section class="row">
                <div class="span8">            
                    <p>Tu puedes participar en la campaña Chile sin Papeleo de varias maneras:</p>
<br>
<h2>Contándonos que trámites quieres que estén en línea: </h2>

<p>Puedes señalar aquellos trámites que preferirías realizar por internet. Para esto sigue los siguientes pasos:</p>

<ol>
    <li>Entra a&nbsp;<a target="_blank" alt="ChileAtiende" href="http://www.chileatiende.cl/">www.chileatiende.cl</a>&nbsp;y busca el trámite que te gustaría que estuviera en línea</li>
    <li>Haz click en el botón&nbsp;<strong>"Quiero este trámite en línea"</strong><img style="cursor: default;" src="' . site_url('assets/img/tramitelinea.png') . '"></li>
    <li>Llena el formulario contándonos porqué el trámite debiera realizarse por internet</li>
    <li>Envíanos el formulario</li>
    <li>Con tus aportes definiremos las prioridades del plan de digitalización de trámites 2013</li>
</ol><br>
<h2>Contándonos qué trámites en línea quieres mejorar</h2>

<p>Puedes señalar aquellos trámites que están en línea pero siguen siendo complicados, lentos o demorosos. Para esto sigue los siguientes pasos:</p>

<ol>
    <li>Entra a&nbsp;<a target="_blank" alt="ChileAtiende" href="http://www.chileatiende.cl/">www.chileatiende.cl</a>&nbsp;y busca el trámite que te gustaría mejorar</li>
    <li>Haz click en el botón&nbsp;<strong>"¿Podemos mejorar este trámite?"</strong><img src="' . site_url('assets/img/mejorartramite.png') . '"></li>
    <li>Llena el formulario contándonos qué debiera mejorar de este trámite</li>
    <li>Envíanos el formulario</li>
    <li>Con tus aportes definiremos las prioridades del plan de mejoramiento de trámites 2013</li>
</ol><br>

<h2>Informándote:</h2>

<p>Visitando periódicamente este sitio podrás revisar en detalle el avance de los compromisos de simplificación y digitalización de los trámites comprometidos por las instituciones públicas.</p>
                </div>
                <div class="span3 offset1">
                    <script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
                    <script src="' . site_url('/assets/js/eltwitter.js') . '"></script>
                </div>
            </section>';

        $this->load->view('template-portada', $aData);
        $this->output->cache(5);
    }

    public function ver($amigable) {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        if($amigable == 'sigue-el-avance' || $amigable == 'peticiones-ciudadanas'){
            if($cachedView = $this->cache->get(sha1($_SERVER['REQUEST_URI']))){
                echo $cachedView;
                return true;
            }
        }

        $aData['active_menu'] = $amigable;

        if($amigable != 'sigue-el-avance' && $amigable != 'peticiones-ciudadanas'){
            $this->load->model('Pagina');
            $contenido = $this->Pagina->getPagina($amigable);
          }else{
              $contenido = $this->sigueElAvance($amigable == 'sigue-el-avance');
          }
        
        $aData['title'] = $contenido->titulo;

        $aData['photobooth'] = '<section class="row photoBooth">
                <div class="span12">
                    <header class="page-header">
                        '.(isset($contenido->compartir)?$contenido->compartir:"").'
                        <h1>' . $contenido->titulo . '<small>' . $contenido->subtitulo . '</small></h1>
                    </header>
                </div>
            </section>';
        if($amigable != 'sigue-el-avance' && $amigable != 'peticiones-ciudadanas'){
            $aData['stuff'] = '<section class="row socialStuff">
                    <div class="span12"></div>
                    <div class="span9">
                        ' . $contenido->contenido . '
                    </div>
                    <div class="span3">
                        <ul class="thumbnails">
                            <li class="span3">
                                <a href="' . site_url('paginas/ver/como-participar') . '" class="thumbnail">
                                    <img src="' . site_url('assets/img/banner/banner1.png') . '" alt="acá va el proceso">
                                </a>
                            </li>
                            <li class="span3">
                                <a href="' . site_url('paginas/ver/instructivo-presidencial') . '" class="thumbnail">
                                    <img src="' . site_url('assets/img/banner/banner3.png') . '" alt="Firma instructivo presidencial">
                                </a>
                            </li>
                        </ul>
                    </div>
                </section>';
            $this->output->cache(5);
            $this->load->view('template-interior', $aData);
        }else{
            $aData['stuff'] = '<section class="row socialStuff">
                    <div class="span12">
                    ' . $contenido->contenido . '
                    </div>
                </section>';
            /*$this->cache->save(sha1($_SERVER['REQUEST_URI']), $this->load->view('template-isotope', $aData, true), 300);
            $maxage = 300;
            header ("Cache-Control: max-age=$maxage");
            header ('Expires: ' . gmstrftime("%a, %d %b %Y %H:%M:%S GMT", time() + $maxage));*/
            $this->load->view('template-isotope', $aData);
        }
    }

    public function sigueElAvance($sello_chilesinpapeleo = false){
        $contenido = new stdClass();
        $this->load->model('Formulario_model');

        $this->load->library('pagination');

        $aData['sello_chilesinpapeleo'] = $sello_chilesinpapeleo;
        $aData['texto_compartir'] = 'ChileSinPapeleo - Sigue el avance.';

        $options = array();

        $institucion = $this->input->get('instituciones')?$this->input->get('instituciones'):null;
        $orderby = $this->input->get('orderby')?$this->input->get('orderby'):'cant_registros';
        $orderdir = $this->input->get('orderdir')?$this->input->get('orderdir'):'DESC';
        $peticiones = $this->input->get('peticiones')?$this->input->get('peticiones'):null;
        $proactivas = $this->input->get('proactivas')?$this->input->get('proactivas'):null;
        $mejoras = $this->input->get('mejoras')?$this->input->get('mejoras'):null;

        if(!$sello_chilesinpapeleo && $mejoras === null)
            $mejoras = 0;

        if($institucion)
            $options['institucion'] = $institucion;
        if($sello_chilesinpapeleo)
            $options['sello_chilesinpapeleo'] = true;
        if($peticiones)
            $options['peticiones'] = true;
        if($proactivas)
            $options['proactivas'] = true;

        $options['mejoras'] = $mejoras;

        $aData['total'] = $this->Formulario_model->getTramites(true, $options);
        $aData['limit']  = 40;
        $aData['offset']  = $this->input->get('offset')?$this->input->get('offset'):0;
        $aData['tramites'] = $this->Formulario_model->getTramites(false, $options, array($orderby => $orderdir), $aData['limit'], $aData['offset']);

        $aData['orderby'] = $orderby;
        $aData['orderdir'] = $orderdir;
        $aData['peticiones'] = $peticiones;
        $aData['proactivas'] = $proactivas;
        $aData['mejoras'] = $mejoras;
        $aData['selectedInstitucion'] = $institucion;
        $aData['max_denuncias'] = $this->Formulario_model->getMaxDenuncias();
        $aData['total_instituciones'] = $this->Formulario_model->getInstituciones(true);
        $aData['instituciones'] = $this->Formulario_model->getInstituciones(false, 't.institucion', 'ASC', $sello_chilesinpapeleo);
        $aData['razones'] = $this->Formulario_model->getRazones();
        $aData['total_denuncias'] = $this->Formulario_model->getResultados(true);

        if($sello_chilesinpapeleo){
            $aData['total_digitalizados'] = 577;//$this->Formulario_model->getTotalDigitalizados();
            $aData['total_peticiones_digitalizadas'] = 28785;//$this->Formulario_model->getCantidadPeticionesDigitalizadas();
            $aData['total_con_peticiones'] = 137;//$this->Formulario_model->getCantidadTramitesConPeticiones();
        }

        $pagination_config['base_url'] = current_url().'?orderby='.$orderby.'&orderdir='.$orderdir.($institucion?'&instituciones='.$institucion:'').($mejoras?'&mejoras=1':'').($peticiones?'&peticiones=1':'').($proactivas?'&proactivas=1':'');
        $pagination_config['total_rows'] = $aData['total'];
        $pagination_config['per_page'] = $aData['limit'];

        $this->pagination->initialize($pagination_config);

        $aData['pagination'] = $this->pagination->create_links();

        $contenido->titulo = 'Sigue el avance';
        $contenido->contenido = $this->load->view($sello_chilesinpapeleo?'sigue-el-avance':'peticiones-ciudadanas', $aData, true);
        $contenido->subtitulo = '&nbsp;conoce las cifras';

        $contenido->compartir = $this->load->view('compartir', $aData, true);

        return $contenido;
    }

}

?>