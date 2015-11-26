<?php
/**
* Comtrolador para obtener los datos mediante rss
*/
class Rss extends CI_Controller
{
    
    public function index($limit = 4)
    {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        header("Content-Type: application/xml; charset=UTF-8");
        if($cachedView = $this->cache->get(sha1($_SERVER['REQUEST_URI']))){
            echo $cachedView;
            return true;
        }

        $this->load->model('Formulario_model');
        
        $orderby = 'cant_registros';
        $orderdir = 'DESC';
        $offset = 0;
        $limit = 4;

        $aData['tramites'] = $this->Formulario_model->getTramites(false, array('periodo' => 'mes'), array($orderby => $orderdir), $limit, $offset);

        $view = $this->load->view('rss', $aData, true);

        $this->cache->save(sha1($_SERVER['REQUEST_URI']), $view, 300);
        echo $view;
    }

}
?>