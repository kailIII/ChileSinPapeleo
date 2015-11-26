<?php

Class Pagina extends CI_Model {
    
    var $titulo = '';
    var $amigable = '';
    var $subtitulo = '';
    var $contenido = '';
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    public function getPaginas() {
        $paginas = $this->db->get('pagina');
        
        return $paginas->result();
    }
    
    public function getPagina($amigable) {
        $this->db->from('pagina');
        $this->db->where('amigable',$amigable);
        
        $pagina = $this->db->get();
        return $pagina->row();
    }
    
    public function insertPagina() {
        $this->titulo = $this->input->post('titulo');
        $this->amigable = url_title(stringsHelper::sanitize_string($this->input->post('titulo')),'-',TRUE);
        $this->subtitulo = $this->input->post('subtitulo');
        $this->contenido = $this->input->post('contenido');
        
        $this->db->insert('pagina', $this);
    }
    
    public function updatePagina($id) {
        $this->titulo = $this->input->post('titulo');
        $this->amigable = url_title(stringsHelper::sanitize_string($this->input->post('titulo')),'-',TRUE);
        $this->subtitulo = $this->input->post('subtitulo');
        $this->contenido = $this->input->post('contenido');
        
        $this->db->update('pagina', $this, 'id = '.$id);
    }
    
}

?>
