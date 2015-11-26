<?php
class Avance_model extends CI_Model {

    var $iteracion = 1;

    public function getIteracion()
    {
        return '_'.$this->iteracion;
    }

    public function getInstituciones($options)
    {
        $this->db->select('servicio.nombre');
        $this->db->select('servicio.codigo');
        $this->db->select_avg('avance_tramites.comprometido'.$this->getIteracion(), 'comprometido');
        $this->db->select_avg('avance_tramites.cumplido'.$this->getIteracion(), 'cumplido');
        $this->db->select('COUNT(avance_tramites.id) AS cant_tramites');
        $this->db->select('SUM(CASE WHEN avance_tramites.cumplido'.$this->getIteracion().' = 100 THEN 1 ELSE 0 END) AS cant_digitalizados');
        $this->db->select('((SUM(CASE WHEN avance_tramites.cumplido'.$this->getIteracion().' = 100 THEN 1 ELSE 0 END)*100)/COUNT(avance_tramites.id)) AS porc_digitalizados');
        $this->db->from('avance_tramites');
        $this->db->join('servicio', 'servicio.codigo = avance_tramites.codigo_servicio');
        $this->db->group_by('servicio.codigo');

        if(isset($options['porc_digitalizados_max'])){
            if(in_array($options['porc_digitalizados_min'], array(100,0)))
                $this->db->having('cumplido <=', $options['porc_digitalizados_max']);
            else
                $this->db->having('cumplido <', $options['porc_digitalizados_max']);
        }

        if(isset($options['porc_digitalizados_min']))
            $this->db->having('cumplido >=', $options['porc_digitalizados_min']);

        switch ($options['orderby']) {
            case 'cant_tramites':
                $this->db->order_by('cant_tramites', $options['orderdir']);
                break;
            case 'porc_digitalizados':
                $this->db->order_by('porc_digitalizados', $options['orderdir']);
                break;
            default:
                $this->db->order_by('servicio.nombre', $options['orderdir']);
                break;
        }
            
        $result = $this->db->get()->result();

        return $result;
    }

    public function getRangos()
    {
        $rangos = array(100 => '100-100');
        for($i = 90; $i >= 0; $i-=10){
            $this->db->select('AVG(cumplido'.$this->getIteracion().') AS cumplido');
            $this->db->from('avance_tramites');
            
            $this->db->having('cumplido >=', ($i == 0 ? 1 : $i));

            $this->db->having('cumplido <', $i+10);
            $this->db->group_by('avance_tramites.codigo_servicio');

            $result = $this->db->get()->result();
            $promedios = array();
            foreach ($result as $promedio) {
                array_push($promedios, $promedio->cumplido);
            }

            if(count($promedios))
            $rangos[number_format(array_sum($promedios) / count($promedios))] = ($i+10).'-'.($i == 0 ? 1 : $i);
        }
        $rangos[0] = '0-0';
        return $rangos;
    }

    public function getInstitucion($codigo_institucion)
    {
        $this->db->select('entidad.*');
        $this->db->select('COUNT(avance_tramites.id) AS cant_tramites');
        $this->db->select('SUM(CASE WHEN cumplido'.$this->getIteracion().' = 100 THEN 1 ELSE 0 END) AS cant_digitalizados');
        $this->db->select('((SUM(CASE WHEN avance_tramites.cumplido'.$this->getIteracion().' = 100 THEN 1 ELSE 0 END)*100)/COUNT(avance_tramites.id)) AS porc_digitalizados');
        $this->db->select('COUNT(DISTINCT(servicio.codigo)) AS cant_instituciones');
        $this->db->select_avg('avance_tramites.comprometido'.$this->getIteracion(), 'comprometido');
        $this->db->select_avg('avance_tramites.cumplido'.$this->getIteracion(), 'cumplido');
        $this->db->from('avance_tramites');
        $this->db->join('servicio', 'servicio.codigo = avance_tramites.codigo_servicio');
        $this->db->join('entidad', 'entidad.codigo = servicio.entidad_codigo');
        $this->db->group_by('entidad.codigo');
        $this->db->where('entidad.codigo', $codigo_institucion);
        $result = $this->db->get()->result();

        return $result[0];
    }

    public function getServicios($options)
    {
        $this->db->select('servicio.nombre');
        $this->db->select('servicio.codigo');
        $this->db->select_avg('avance_tramites.comprometido'.$this->getIteracion(), 'comprometido');
        $this->db->select_avg('avance_tramites.cumplido'.$this->getIteracion(), 'cumplido');
        $this->db->select('COUNT(avance_tramites.id) AS cant_tramites');
        $this->db->select('SUM(CASE WHEN cumplido'.$this->getIteracion().' = 100 THEN 1 ELSE 0 END) AS cant_digitalizados');
        $this->db->select('((SUM(CASE WHEN avance_tramites.cumplido'.$this->getIteracion().' = 100 THEN 1 ELSE 0 END)*100)/COUNT(avance_tramites.id)) AS porc_digitalizados');
        $this->db->from('avance_tramites');
        $this->db->join('servicio', 'servicio.codigo = avance_tramites.codigo_servicio');
        $this->db->group_by('servicio.codigo');
        
        if(isset($options['codigo_institucion']))
            $this->db->where('servicio.entidad_codigo', $options['codigo_institucion']);
        
        switch ($options['orderby']) {
            case 'cant_tramites':
                $this->db->order_by('cant_tramites', $options['orderdir']);
                break;
            case 'cumplido':
                $this->db->order_by('cumplido'.$this->getIteracion(), $options['orderdir']);
                break;
            default:
                $this->db->order_by('servicio.nombre', $options['orderdir']);
                break;
        }

        $result = $this->db->get()->result();

        if(isset($options['total']))
            return count($result);
        else
            return $result;
    }

    public function getTramitesServicio($options)
    {
        $this->db->select('avance_tramites.*, cumplido'.$this->getIteracion().' AS cumplido, comprometido'.$this->getIteracion().' AS comprometido, tramites.url');
        $this->db->join('tramites', 'tramites.codigo = avance_tramites.codigo_cha', 'left');
        $this->db->like('codigo_servicio', $options['codigo_servicio'], 'after');

        switch ($options['orderby']) {
            case 'cumplido':
                $this->db->order_by('cumplido'.$this->getIteracion(), $options['orderdir']);
                break;
            default:
                $this->db->order_by('nombre', $options['orderdir']);
                break;
        }

        return $this->db->get('avance_tramites')->result();
    }

    public function getInstitucionPorPorcentajeAvance($options)
    {
        $instituciones = array();
        if(isset($options['tramo'])){
            list($options['porc_digitalizados_max'], $options['porc_digitalizados_min']) = explode('-', $options['rangos'][$options['tramo']]);
            $instituciones = $this->getInstituciones($options);
        }else{
            foreach ($options['rangos'] as $porcentaje => $rangos) {
                list($options['porc_digitalizados_max'], $options['porc_digitalizados_min']) = explode('-', $rangos);
                $instituciones[$porcentaje] = $this->getInstituciones($options);
            }
        }

        return $instituciones;
    }

    public function getTotalTramites()
    {
        return $this->db->count_all('avance_tramites');
    }

    public function actualizaDatosTramite()
    {
        $this->db->select('codigo_cha, nombre');
        $this->db->from('avance_tramites');
        $this->db->where_not_in('codigo_cha ', array('S/Cod', 'S/C'));

        $results = $this->db->get()->result();
        $cant_tramites = 0;
        $info_actualizacion = '<table><tr><th>Codigo CHA</th><th>Nombre en avance CHSP</th><th>Nombre en Chileatiende</th><th>Url Tramite</th></tr>';
        foreach ($results as $tramite) {
            $api_chileatiende = 'https://www.chileatiende.cl/api/fichas/'.$tramite->codigo_cha.'?access_token=jYmvOP5znSfNRocw';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $api_chileatiende);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //Se debe omitir la verificación del certificado ssl
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

            $ficha = json_decode(curl_exec($ch), true);

            if(isset($ficha['ficha'])){
                $cant_tramites++;
                $updateData = array();
                $dataLinea = array('codigo_cha' => $tramite->codigo_cha, 'nombre_chsp' =>$tramite->nombre, 'nombre_cha' => '', 'url_tramite' => '');
                $this->db->where('codigo_cha', $tramite->codigo_cha);

                if($tramite->nombre != $ficha['ficha']['titulo']){
                    $dataLinea['nombre_cha'] = $ficha['ficha']['titulo'];
                    //$info_actualizacion .= '<tr><td>'.$tramite->codigo_cha.'</td><td>'.$tramite->nombre.'</td><td>'.$ficha['ficha']['titulo'].'</td></tr>';
                    $updateData = array(
                        'nombre' => $ficha['ficha']['titulo']
                    );
                }
                if($ficha['ficha']['guia_online_url']){
                    $updateData = array(
                        'url_cha' => $ficha['ficha']['permalink']
                    );
                    $dataLinea['url_tramite'] = $ficha['ficha']['guia_online_url'];
                    //$info_actualizacion .= '<td>'.$ficha['ficha']['guia_online_url'].'</td>';
                }
                if(count($updateData)){
                    $this->db->update('avance_tramites', $updateData);
                    $info_actualizacion .= '<tr><td>'.implode('</td><td>', $dataLinea).'</td></tr>';
                }
            }

        }
        $info_actualizacion .= '<tr><th colspan="3">Tramites actualizados:</th><td>'.$cant_tramites.'</td></tr></table>';

        return $info_actualizacion;
    }
}
?>